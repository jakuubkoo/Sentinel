<?php

namespace App\Tests\Command;

use App\Entity\User;
use App\Manager\UserManager;
use PHPUnit\Framework\TestCase;
use App\Command\GrantAdminRoleCommand;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class GrantAdminRoleCommandTest
 *
 * Test cases for the GrantAdminRoleCommand class.
 *
 * @package App\Tests\Command
 */
class GrantAdminRoleCommandTest extends TestCase
{
    /**
     * Test case for empty email argument.
     *
     * @return void
     */
    public function testGrantAdminEmptyEmail(): void
    {
        // mock UserManager
        $userManager = $this->createMock(UserManager::class);

        // create instance of the command with mocked UserManager
        $command = new GrantAdminRoleCommand($userManager);

        // create CommandTester to execute the command
        $commandTester = new CommandTester($command);

        // execute command with empty email
        $commandTester->execute([
            'email' => ''
        ]);

        // assert error message
        $this->assertStringContainsString('You must add the admin email argument!', $commandTester->getDisplay());
    }

    /**
     * Test case for non-existing user email.
     *
     * @return void
     */
    public function testGrantAdminNonExistingUser(): void
    {
        // mock UserManager
        $userManager = $this->createMock(UserManager::class);

        // set up UserManager mock behavior
        $userManager->expects($this->any())->method('getUserRepo')->willReturn(null);

        // create instance of the command with mocked UserManager
        $command = new GrantAdminRoleCommand($userManager);

        // create CommandTester to execute the command
        $commandTester = new CommandTester($command);

        // execute command with non-existing user email
        $commandTester->execute([
            'email' => 'nonexisting@example.com'
        ]);

        // assert error message
        $this->assertStringContainsString('Error email: nonexisting@example.com is not registered!', $commandTester->getDisplay());
    }

    /**
     * Test case for already admin user email.
     *
     * @return void
     */
    public function testGrantAlreadyAdmin(): void
    {
        // mock UserManager
        $userManager = $this->createMock(UserManager::class);

        // set up UserManager mock behavior
        $userManager->expects($this->any())->method('getUserRepo')->willReturn(new User());
        $userManager->expects($this->any())->method('isUserAdmin')->willReturn(true);

        // create instance of the command with mocked UserManager
        $command = new GrantAdminRoleCommand($userManager);

        // create CommandTester to execute the command
        $commandTester = new CommandTester($command);

        // execute command with already admin user email
        $commandTester->execute([
            'email' => 'existing@example.com'
        ]);

        // assert error message
        $this->assertStringContainsString('User: existing@example.com is already admin', $commandTester->getDisplay());
    }

    /**
     * Test case for successful grant of admin role.
     *
     * @return void
     */
    public function testGrantAdminSuccess(): void
    {
        // mock UserManager
        $userManager = $this->createMock(UserManager::class);

        // set up UserManager mock behavior
        $userManager->expects($this->any())->method('getUserRepo')->willReturn(new User());
        $userManager->expects($this->any())->method('isUserAdmin')->willReturn(false);
        $userManager->expects($this->once())->method('addAdminRoleToUser')->with('existing@example.com');

        // create instance of the command with mocked UserManager
        $command = new GrantAdminRoleCommand($userManager);

        // create CommandTester to execute the command
        $commandTester = new CommandTester($command);

        // execute command with valid non-admin user email
        $commandTester->execute([
            'email' => 'existing@example.com'
        ]);

        // assert success message
        $this->assertStringContainsString('admin role granted to: existing@example.com', $commandTester->getDisplay());
    }
}

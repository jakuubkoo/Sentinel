<?php

namespace App\Tests\Command;

use App\Manager\UserManager;
use PHPUnit\Framework\TestCase;
use App\Command\UserRegisterCommand;
use App\Entity\User;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class UserRegisterCommandTest
 *
 * Test case for the UserRegisterCommand class.
 *
 * @package App\Tests\Command
 */
class UserRegisterCommandTest extends TestCase
{
    /**
     * Test execution of the command with an invalid email.
     *
     * @return void
     */
    public function testExecuteWithInvalidEmail(): void
    {
        // mock UserManager
        $userManagerMock = $this->createMock(UserManager::class);
        $userManagerMock->method('getUserRepo')->willReturn(null);

        // create command instance
        $command = new UserRegisterCommand($userManagerMock);

        // create CommandTester instance
        $commandTester = new CommandTester($command);

        // execute the command with invalid email argument
        $commandTester->execute([
            'email' => 'invalidemail', // invalid email format
        ]);

        // assert the command output contains error message
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('The email address format is invalid!', $output);
    }

    /**
     * Test execution of the command with an existing email.
     *
     * @return void
     */
    public function testExecuteWithExistingEmail(): void
    {
        // Mock UserManager
        $userManagerMock = $this->createMock(UserManager::class);
        // Configure UserManager mock behavior to simulate an existing email
        $userManagerMock->method('getUserRepo')->willReturn(new User());

        // Create command instance with mocked UserManager
        $command = new UserRegisterCommand($userManagerMock);

        // Create CommandTester instance
        $commandTester = new CommandTester($command);

        // Execute the command with an existing email argument
        $commandTester->execute([
            'email' => 'test@test.com', // Existing email
        ]);

        // Assert that the command output contains the error message
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Error email: test@test.com is already used!', $output);
    }

    /**
     * Test execution of the command with a valid email.
     *
     * @return void
     */
    public function testExecuteWithValidEmail(): void
    {
        // mock UserManager
        $userManagerMock = $this->createMock(UserManager::class);
        $userManagerMock->method('getUserRepo')->willReturn(null); // simulate non-existing email

        // create command instance
        $command = new UserRegisterCommand($userManagerMock);

        // create CommandTester instance
        $commandTester = new CommandTester($command);

        // execute the command with valid email argument
        $commandTester->execute([
            'email' => 'newuser@example.com', // valid email
        ]);

        // assert the command output contains success message
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('New user registred email: newuser@example.com', $output);
    }
}

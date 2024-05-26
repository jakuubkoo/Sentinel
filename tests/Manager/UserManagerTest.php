<?php

namespace App\Tests\Manager;

use App\Entity\User;
use App\Util\SecurityUtil;
use App\Manager\LogManager;
use App\Manager\UserManager;
use App\Manager\ErrorManager;
use App\Util\VisitorInfoUtil;
use PHPUnit\Framework\TestCase;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class UserManagerTest
 *
 * Test cases for the UserManager class.
 *
 * @package App\Tests\Manager
 */
class UserManagerTest extends TestCase
{
    private MockObject|LogManager $logManager;
    private MockObject|SecurityUtil $securityUtil;
    private MockObject|ErrorManager $errorManager;
    private MockObject|VisitorInfoUtil $visitorInfoUtil;
    private MockObject|EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->logManager = $this->createMock(LogManager::class);
        $this->securityUtil = $this->createMock(SecurityUtil::class);
        $this->errorManager = $this->createMock(ErrorManager::class);
        $this->visitorInfoUtil = $this->createMock(VisitorInfoUtil::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
    }

    /**
     * Test case for the getUserRepo method.
     *
     * @return void
     */
    public function testGetUserRepo(): void
    {
        // mock search criteria
        $search = ['email' => 'test@example.com'];

        // mock UserRepository
        $userRepository = $this->createMock(UserRepository::class);

        // mock EntityManager to return UserRepository
        $this->entityManager->expects($this->once())
            ->method('getRepository')
            ->willReturn($userRepository);

        // mock findOneBy method on UserRepository
        $userRepository->expects($this->once())
            ->method('findOneBy')
            ->with($search)
            ->willReturn(new User());

        // create UserManager instance
        $userManager = new UserManager($this->logManager, $this->securityUtil, $this->visitorInfoUtil, $this->errorManager, $this->entityManager);

        // call getUserRepo method
        $result = $userManager->getUserRepo($search);

        // assert that result is an instance of User
        $this->assertInstanceOf(User::class, $result);
    }

    /**
     * Test case for the registerUser method.
     *
     * @return void
     */
    public function testRegisterUser(): void
    {
        // create UserManager instance
        $userManager = new UserManager($this->logManager, $this->securityUtil, $this->visitorInfoUtil, $this->errorManager, $this->entityManager);

        // mock user registration data
        $email = 'test@example.com';
        $password = 'password123';

        // mock UserRepository
        $userRepository = $this->createMock(UserRepository::class);

        // mock EntityManager to return UserRepository
        $this->entityManager->expects($this->atLeastOnce())
            ->method('getRepository')
            ->willReturn($userRepository);

        // mock findOneBy method on UserRepository
        $userRepository->expects($this->atLeastOnce())
            ->method('findOneBy')
            ->willReturn(null);

        // mock SecurityUtil to return a hashed password
        $this->securityUtil->expects($this->once())
            ->method('generateHash')
            ->willReturn('hashed_password');

        // mock VisitorInfoUtil to return an IP address
        $this->visitorInfoUtil->expects($this->once())
            ->method('getIP')
            ->willReturn('127.0.0.1');

        // mock EntityManager to persist and flush user
        $this->entityManager->expects($this->once())
            ->method('persist');
        $this->entityManager->expects($this->once())
            ->method('flush');

        // mock LogManager to log registration action
        $this->logManager->expects($this->once())
            ->method('log');

        // call registerUser method
        $userManager->registerUser($email, $password);
    }
}

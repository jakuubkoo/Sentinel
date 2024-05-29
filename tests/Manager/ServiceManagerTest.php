<?php

namespace App\Tests\Manager;

use App\Entity\Service;
use App\Manager\EmailManager;
use App\Manager\ErrorManager;
use App\Manager\ServiceManager;
use PHPUnit\Framework\TestCase;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class ServiceManagerTest
 *
 * The ServiceManagerTest class is responsible for testing the ServiceManager class.
 *
 * @package App\Tests\Manager
 */
class ServiceManagerTest extends TestCase
{
    private MockObject|EmailManager $emailManagerMock;
    private MockObject|ErrorManager $errorManagerMock;
    private MockObject|ServiceManager $serviceManager;
    private MockObject|UserRepository $userRepositoryMock;
    private MockObject|EntityManagerInterface $entityManagerMock;

    /**
     * Set up the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->emailManagerMock = $this->createMock(EmailManager::class);
        $this->errorManagerMock = $this->createMock(ErrorManager::class);
        $this->userRepositoryMock = $this->createMock(UserRepository::class);
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);

        $this->serviceManager = new ServiceManager(
            $this->emailManagerMock,
            $this->errorManagerMock,
            $this->userRepositoryMock,
            $this->entityManagerMock
        );
    }

    /**
     * Test get service repo.
     *
     * @return void
     */
    public function testGetServiceRepo(): void
    {
        $serviceMock = $this->createMock(Service::class);

        // set up the entity manager mock
        $this->entityManagerMock->method('getRepository')->willReturn($this->createConfiguredMock(\Doctrine\ORM\EntityRepository::class, ['findOneBy' => $serviceMock]));

        // assert that the service repo is returned
        $result = $this->serviceManager->getServiceRepo(['id' => 1]);

        // assert that the service repo is returned
        $this->assertSame($serviceMock, $result);
    }

    /**
     * Test get all services.
     *
     * @return void
     */
    public function testGetAllServices(): void
    {
        // create mocks
        $serviceMock = $this->createMock(Service::class);

        // set up the entity manager mock
        $this->entityManagerMock->method('getRepository')->willReturn($this->createConfiguredMock(\Doctrine\ORM\EntityRepository::class, ['findAll' => [$serviceMock]]));

        // assert that the service repo is returned
        $result = $this->serviceManager->getAllServices();

        // assert that the service repo is returned
        $this->assertContains($serviceMock, $result);
    }

    /**
     * Test update service status.
     *
     * @return void
     */
    public function testUpdateServiceStatus(): void
    {
        // create mocks
        $serviceMock = $this->createMock(Service::class);
        $serviceRepoMock = $this->createMock(Service::class);
        $userEmails = ['user@example.com'];
        $serviceName = 'Test Service';

        // set up the service mock
        $serviceMock->method('getId')->willReturn(1);
        $serviceRepoMock->method('getUserIds')->willReturn([1, 2, 3]);
        $serviceRepoMock->method('getLastStatus')->willReturn('online');
        $serviceRepoMock->method('getName')->willReturn($serviceName);

        // set up the entity manager mock
        $this->entityManagerMock->method('getRepository')->willReturn($this->createConfiguredMock(\Doctrine\ORM\EntityRepository::class, ['findOneBy' => $serviceRepoMock]));
        $this->userRepositoryMock->method('findEmailsByIds')->willReturn($userEmails);

        // set up the service manager mock
        $serviceRepoMock->expects($this->once())->method('setLastStatus')->with('offline');
        $serviceRepoMock->expects($this->once())->method('setLastCheckTime')->with($this->isInstanceOf(\DateTime::class));

        // set up the entity manager mock
        $this->entityManagerMock->expects($this->once())->method('flush');

        // assert that the email manager sendServiceDownEmail method is called
        $this->emailManagerMock->expects($this->once())->method('sendServiceDownEmail')->with($userEmails, $serviceName);

        // call the updateServiceStatus method
        $this->serviceManager->updateServiceStatus($serviceMock, 'offline');
    }

    /**
     * Test update service status handle error.
     *
     * @return void
     */
    public function testUpdateServiceStatusHandleError(): void
    {
        // create mocks
        $serviceMock = $this->createMock(Service::class);
        $serviceMock->method('getId')->willReturn(1);

        // set up the entity manager mock
        $this->entityManagerMock->method('getRepository')->willReturn($this->createConfiguredMock(\Doctrine\ORM\EntityRepository::class, ['findOneBy' => null]));

        // assert that the error manager handleError method is called
        $this->errorManagerMock->expects($this->once())->method('handleError')->with('Service id: 1 not found', 404);

        // call the updateServiceStatus method
        $this->serviceManager->updateServiceStatus($serviceMock, 'offline');
    }
}

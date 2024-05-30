<?php

namespace App\Manager;

use App\Entity\Service;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class ServiceManager
 *
 * This class is responsible for managing services.
 *
 * @package App\Manager
 */
class ServiceManager
{
    private EmailManager $emailManager;
    private ErrorManager $errorManager;
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(EmailManager $emailManager, ErrorManager $errorManager, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->emailManager = $emailManager;
        $this->errorManager = $errorManager;
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    /**
     * Method to find services repisitory by search criteria.
     *
     * @param array<mixed> $search
     *
     * @return Service|null
     */
    public function getServiceRepo(array $search): ?Service
    {
        // get service repo
        return $this->entityManager->getRepository(Service::class)->findOneBy($search);
    }

    /**
     * Method to get all services.
     *
     * @return array<mixed>
     */
    public function getAllServices(): array
    {
        return $this->entityManager->getRepository(Service::class)->findAll();
    }

    /**
     * Method to update service status.
     *
     * @param Service $service
     * @param string $status
     *
     * @return void
     */
    public function updateServiceStatus(Service $service, string $status): void
    {
        // get service id
        $id = $service->getId();

        // get service repo
        $serviceRepo = $this->getServiceRepo(['id' => $id]);

        // check if repo found
        if ($serviceRepo != null) {
            // get user emails
            $userEmails = $this->userRepository->findEmailsByIds($serviceRepo->getUserIds());

            // get last status
            $lastStatus = $serviceRepo->getLastStatus();

            // update service status
            $serviceRepo->setLastStatus($status);
            $serviceRepo->setLastCheckTime(new \DateTime());

            // flush changes
            try {
                $this->entityManager->flush();
            } catch (\Exception $e) {
                $this->errorManager->handleError('error to update service status: ' . $e->getMessage(), 500);
            }

            // send offline notification
            if ($status == 'offline' && $lastStatus == 'online') {
                $this->emailManager->sendServiceDownEmail($userEmails, $serviceRepo->getName());
            }

            // send back online notification
            if ($lastStatus == 'offline' && $status == 'online') {
                $this->emailManager->sendServiceBackOnline($userEmails, $serviceRepo->getName());
            }
        } else {
            $this->errorManager->handleError('Service id: ' . $id . ' not found', 404);
        }
    }
}

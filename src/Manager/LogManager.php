<?php

namespace App\Manager;

use App\Entity\Log;
use App\Util\VisitorInfoUtil;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

/**
 * Class LogManager
 *
 * This class is responsible for managing log entries.
 *
 * @package App\Manager
 */
class LogManager
{
    private ErrorManager $errorManager;
    private VisitorInfoUtil $visitorInfoUtil;
    private EntityManagerInterface $entityManager;

    public function __construct(ErrorManager $errorManager, VisitorInfoUtil $visitorInfoUtil, EntityManagerInterface $entityManager)
    {
        $this->errorManager = $errorManager;
        $this->entityManager = $entityManager;
        $this->visitorInfoUtil = $visitorInfoUtil;
    }

    /**
     * Creates and saves a log entry.
     *
     * @param string $name The name of the log entry.
     * @param string $message The message of the log entry.
     *
     * @return void
     */
    public function log(string $name, string $message): void
    {
        // inti log entity
        $log = new Log();

        // set log entity values
        $log->setName($name);
        $log->setMessage($message);

        // set visitor info
        $log->setTime(new DateTime());
        $log->setIpAddress($this->visitorInfoUtil->getIP());

        // save log entity
        try {
            $this->entityManager->persist($log);
            $this->entityManager->flush();
        } catch (Exception $e) {
            $this->errorManager->handleError('log-error: ' . $e->getMessage(), 500);
        }
    }
}

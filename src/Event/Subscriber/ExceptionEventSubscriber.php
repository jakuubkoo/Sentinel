<?php

namespace App\Event\Subscriber;

use App\Util\SiteUtil;
use App\Manager\LogManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ExceptionEventSubscriber
 *
 * Subscriber to handle internal (profiler) errors.
 *
 * @package App\EventSubscriber
 */
class ExceptionEventSubscriber implements EventSubscriberInterface
{
    private SiteUtil $siteUtil;
    private LogManager $logManager;
    private LoggerInterface $logger;

    public function __construct(SiteUtil $siteUtil, LogManager $logManager, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->siteUtil = $siteUtil;
        $this->logManager = $logManager;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array<string> The event names to listen to
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    /**
     * Method called when the KernelEvents::EXCEPTION event is dispatched.
     *
     * @param ExceptionEvent $event The event object
     *
     * @return void
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        // get the exception
        $exception = $event->getThrowable();

        // get the exception message
        $message = $exception->getMessage();

        // check if the error message should be logged to the database
        if ($this->isDatabaseLogAllowed($message)) {
            // log the exception to mysql database
            $this->logManager->log('exception', $message);
        }

        // log the error message with monolog
        $this->logger->error($message);

        // init response type
        $response = new JsonResponse();

        // protect error message in production mode
        if (!$this->siteUtil->isDevMode()) {
            $message = 'Unexpected server error';
        }

        // set response data
        $responseData = [
            'status' => 'error',
            'code' => $exception->getCode(),
            'message' => $message,
        ];

        // set response data
        $response->setData($responseData);

        // send response
        $event->setResponse($response);
    }

    /**
     * Checks if an event can be logged based on the error message.
     *
     * @param string $errorMessage The error message to be checked.
     *
     * @return bool Returns true if the event can be dispatched, otherwise false.
     */
    public function isDatabaseLogAllowed(string $errorMessage): bool
    {
        // list of error patterns that should block event dispatch
        $blockedErrorPatterns = [
            'log-error',
            'Unknown database',
            'Base table or view not found',
            'An exception occurred in the driver'
        ];

        // loop through each blocked error pattern
        foreach ($blockedErrorPatterns as $pattern) {
            // check if the current pattern exists in the error message
            if (strpos($errorMessage, $pattern) !== false) {
                // if a blocked pattern is found, return false
                return false;
            }
        }

        // if no blocked patterns are found, return true
        return true;
    }
}

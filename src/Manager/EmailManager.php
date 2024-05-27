<?php

namespace App\Manager;

use App\Util\SiteUtil;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class EmailManager
{
    private SiteUtil $siteUtil;
    private MailerInterface $mailer;
    private ErrorManager $errorManager;

    /**
     * Class constructor.
     *
     * @param SiteUtil $siteUtil The site util instance.
     * @param ErrorManager $errorManager The error manager instance.
     * @param MailerInterface $mailer The mailer interface instance.
     */
    public function __construct(SiteUtil $siteUtil, ErrorManager $errorManager, MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        $this->siteUtil = $siteUtil;
        $this->errorManager = $errorManager;
    }

    /**
     * Sends service down email to multiple recipients.
     *
     * @param array<string> $recipients The recipients of the email.
     * @param string $serviceName The name of the service that is down.
     * @param string $from The email address of the sender.
     *
     * @return void
     */
    public function sendServiceDownEmail(array $recipients, string $serviceName, string $from): void
    {
        // Sends multiple emails to all recipients
        $this->sendMultipleEmails(
            $recipients,
            $serviceName . ' Service is Down!',
            'Service ' . $serviceName . ' is currently down. Please check your service.',
            $from
        );
    }

    /**
     * Sends multiple emails to an array of recipients.
     *
     * @param array<string> $recipients An array of email addresses of the recipients.
     * @param string $title The title or subject of the email.
     * @param string $body The email body content.
     * @param string $from The email address of the sender.
     *
     * @throws TransportExceptionInterface The transport exception interface
     *
     * @return void
     */
    public function sendMultipleEmails(array $recipients, string $title, string $body, string $from): void
    {
        // check if email sending is enabled
        if (!$this->siteUtil->isEmailingEnabled()) {
            return;
        }

        // build email message
        $email = (new Email())
            ->from($from)
            ->to(...$recipients)
            ->subject($title)
            ->html('<p>' . $body . '</p>');

        // send email
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->errorManager->handleError('Error sending email: ' . $e->getMessage(), 500);
        }
    }
}

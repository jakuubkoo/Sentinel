<?php

namespace App\Manager;

use App\Util\SiteUtil;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
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
     * @param SiteUtil $siteUtil The SiteUtil instance.
     * @param ErrorManager $errorManager The ErrorManager instance.
     * @param MailerInterface $mailer The MailerInterface instance.
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
     *
     * @return void
     */
    public function sendServiceDownEmail(array $recipients, string $serviceName): void
    {
        // Sends multiple emails to all recipients
        $this->sendMultipleEmails(
            $recipients,
            $serviceName . ' Service is Down!',
            'Service ' . $serviceName . ' is currently down. Please check your service.'
        );
    }

    /**
     * Sends multiple emails to an array of recipients.
     *
     * @param array<string> $recipients An array of email addresses of the recipients.
     * @param string $title The title or subject of the email.
     * @param string $body The email body content.
     *
     * @return void
     */
    public function sendMultipleEmails(array $recipients, string $title, string $body): void
    {
        // check if email sending is enabled
        if (!$this->siteUtil->isEmailingEnabled()) {
            return;
        }

        // build email message
        $email = (new TemplatedEmail())
            ->from('support@edgetracker.app')
            ->to(...$recipients)
            ->subject($title)
            ->htmlTemplate('emails/service_down_notification.html.twig')
            ->context([
                'username' => 'Developers',
                'service' => $body,
                'action_url' => 'https://sentinel.com/status', // Replace with your actual status URL
            ]);

        // send email
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->errorManager->handleError('Error sending email: ' . $e->getMessage(), 500);
        }
    }
}

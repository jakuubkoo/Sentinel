<?php

namespace App\Manager;

use App\Util\SiteUtil;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

/**
 * Class EmailManager
 *
 * The EmailManager class is responsible for sending emails to multiple recipients.
 *
 * @package App\Manager
 */
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
            'service_down_notification',
            'Service ' . $serviceName . ' is currently down.'
        );
    }

    /**
     * Sends service online up email to multiple recipients.
     *
     * @param array<string> $recipients The recipients of the email.
     * @param string $serviceName The name of the service that is back online.
     *
     * @return void
     */
    public function sendServiceBackOnline(array $recipients, string $serviceName): void
    {
        // sends multiple emails to all recipients
        $this->sendMultipleEmails(
            $recipients,
            $serviceName . ' Service is Back online!',
            'service_online_notification',
            'Service ' . $serviceName . ' is back online.'
        );
    }

    /**
     * Sends multiple emails to an array of recipients.
     *
     * @param array<string> $recipients An array of email addresses of the recipients.
     * @param string $title The title or subject of the email.
     * @param string $template The email template to use.
     * @param string $body The email body content.
     *
     * @return void
     */
    public function sendMultipleEmails(array $recipients, string $title, string $template, string $body): void
    {
        // check if email sending is enabled
        if (!$this->siteUtil->isEmailingEnabled()) {
            return;
        }

        // build email message
        $email = (new TemplatedEmail())
            ->from($_ENV['MAILER_USERNAME'])
            ->to(...$recipients)
            ->subject($title)
            ->htmlTemplate('emails/' . $template . '.html.twig')
            ->context([
                'username' => 'Developers',
                'body' => $body,
                'action_url' => '#',
            ]);

        // send email
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->errorManager->handleError('Error sending email: ' . $e->getMessage(), 500);
        }
    }
}

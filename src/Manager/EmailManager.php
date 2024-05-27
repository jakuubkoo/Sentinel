<?php

namespace App\Manager;

use App\Util\SiteUtil;
use Exception;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class EmailManager
{
    /**
     * @var MailerInterface $mailer
     */
    private MailerInterface $mailer;

    /**
     * @var SiteUtil
     */
    private SiteUtil $siteUtil;

    /**
     * Class constructor.
     *
     * @param MailerInterface $mailer The mailer interface instance.
     * @param SiteUtil $siteUtil The site util instance.
     */
    public function __construct(MailerInterface $mailer, SiteUtil $siteUtil)
    {
        $this->mailer = $mailer;
        $this->siteUtil = $siteUtil;
    }

    /**
     * Sends service down email to multiple recipients.
     *
     * @param array<string> $recipients The recipients of the email.
     * @param string $serviceName The name of the service that is down.
     *
     * @return void
     * @throws TransportExceptionInterface
     */
    public function sendServiceDownEmail(array $recipients, string $serviceName): void
    {
        // Sends multiple emails to all recipients
        $this->sendMultipleEmails($recipients, $serviceName . ' Service is Down!', 'Service ' . $serviceName . ' is currently down. Please check your service.');
    }

    /**
     * Sends multiple emails to an array of recipients.
     *
     * @param array<string> $recipients An array of email addresses of the recipients.
     * @param string $title The title or subject of the email.
     * @param string $body The email body content.
     *
     * @return void
     * @throws TransportExceptionInterface
     */
    public function sendMultipleEmails(array $recipients, string $title, string $body): void
    {

        if (!$this->siteUtil->isEmailingEnabled()) {
            return;
        }

        $email = (new Email())
            ->from('support@edgetracker.app')
            ->to(...$recipients)
            ->subject($title)
            ->html('<p>' . $body . '</p>');

        $this->mailer->send($email);
    }
}

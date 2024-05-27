<?php

namespace App\Tests\Manager;

use App\Util\SiteUtil;
use App\Manager\EmailManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\MailerInterface;

/**
 * Class EmailManagerTest
 *
 * Test cases for the EmailManager class.
 *
 * @package App\Tests\Manager
 */
class EmailManagerTest extends TestCase
{
    /**
     * Test case for sending service down email.
     *
     * @return void
     */
    public function testSendServiceDownEmail(): void
    {
        // mock SiteUtil
        $siteUtilMock = $this->createMock(SiteUtil::class);
        $siteUtilMock->method('isEmailingEnabled')->willReturn(true);

        // mock MailerInterface
        $mailerMock = $this->createMock(MailerInterface::class);
        $mailerMock->expects($this->once())->method('send');

        // mock ErrorManager
        $errorManagerMock = $this->createMock(\App\Manager\ErrorManager::class);

        // create EmailManager instance
        $emailManager = new EmailManager($siteUtilMock, $errorManagerMock, $mailerMock);

        // send service down email
        $emailManager->sendServiceDownEmail(['recipient1@example.com', 'recipient2@example.com'], 'Test', 'from@example.com');
    }
}

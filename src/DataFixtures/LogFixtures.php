<?php

namespace App\DataFixtures;

use App\Entity\Log;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

/**
 * Class LogFixtures
 *
 * Fixture class for generating random log entries.
 *
 * @package App\DataFixtures
 */
class LogFixtures extends Fixture
{
    /**
     * Array of fake log messages.
     *
     * @var array<string> $logMessages
     */
    private array $logMessages = [
        'User logged in successfully.',
        'Error: Failed to authenticate user.',
        'New user registered.',
        'User profile updated.',
        'Error: Database connection failed.',
        'Product added to cart.',
        'Payment processed successfully.',
        'Warning: Server disk space running low.',
        'Email sent to user.',
        'Error: Server crashed.',
        'User deleted account.',
        'New order received.',
        'Error: Invalid input data.',
        'Product out of stock.',
        'Information: Server restarted.',
        'User password changed.',
        'Error: Application crashed.',
        'File uploaded successfully.',
        'Error: Access denied.',
        'User session expired.',
        'Backup completed.',
        'Task completed successfully.',
        'Error: Server overload detected.',
        'Database backup started.',
        'User profile picture updated.',
        'Error: Server security breach detected.',
        'Newsletter subscription confirmed.',
        'Database migration completed.',
        'Error: Unexpected server error.',
        'User permission changed.',
        'Product review submitted.',
        'Error: Memory leak detected.',
        'User account locked.',
        'File download failed.',
        'Error: Server maintenance in progress.',
        'User session started.',
        'New comment posted.',
        'Error: Software update failed.',
        'Server configuration changed.',
        'Error: Invalid credentials provided.',
        'User account deactivated.',
        'Product price updated.',
        'Task scheduled.',
        'Error: Missing required files.',
        'User profile viewed.',
    ];

    /**
     * Load method to generate log entries.
     *
     * @param ObjectManager $manager The object manager.
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 100; $i++) {
            $log = new Log();
            $log->setName('log-fixture');
            $log->setMessage($this->logMessages[array_rand($this->logMessages)]);
            $log->setTime(new \DateTime());
            $log->setIpAddress('127.0.0.1');
            $manager->persist($log);
        }

        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Service;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

/**
 * Fixture class for loading user data into the database.
 *
 * This class defines a fixture for creating a user entity with predefined data. It generates a random API token and uses the SecurityUtil service to hash the password.
 *
 * @package App\DataFixtures
 */
class ServiceFixtures extends Fixture
{
    /**
     * Loads data fixtures into the database.
     *
     * @param ObjectManager $manager The object manager instance
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        // http service
        $service = new Service();
        $service->setName('becvar-site');
        $service->setDescription('The personal website');
        $service->setType('http');
        $service->setTarget('https://www.becvar.xyz');
        $service->setPort(443);
        $service->setMaxTimeout(5);
        $service->setHttpAcceptCode(200);
        $service->setLastCheckTime(new \DateTime());
        $service->setLastStatus('non-checked');
        // persist the service
        $manager->persist($service);

        // mysql service
        $service = new Service();
        $service->setName('mysql');
        $service->setDescription('The MySQL database');
        $service->setType('internal');
        $service->setTarget('mysql');
        $service->setPort(3306);
        $service->setMaxTimeout(5);
        $service->setHttpAcceptCode(null);
        $service->setLastCheckTime(new \DateTime());
        $service->setLastStatus('non-checked');
        // persist the service
        $manager->persist($service);

        // flush data
        $manager->flush();
    }
}

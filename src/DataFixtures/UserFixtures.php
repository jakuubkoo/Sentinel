<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\User;
use App\Util\SecurityUtil;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\ByteString;
use Doctrine\Bundle\FixturesBundle\Fixture;

/**
 * Fixture class for loading user data into the database.
 *
 * This class defines a fixture for creating a user entity with predefined data. It generates a random API token and uses the SecurityUtil service to hash the password.
 *
 * @package App\DataFixtures
 */
class UserFixtures extends Fixture
{
    private SecurityUtil $securityUtil;

    /**
     * UserFixtures constructor.
     *
     * @param SecurityUtil $securityUtil The security utility instance.
     */
    public function __construct(SecurityUtil $securityUtil)
    {
        $this->securityUtil = $securityUtil;
    }

    /**
     * Loads data fixtures into the database.
     *
     * @param ObjectManager $manager The object manager instance
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $user = new User();

        // set user data
        $user->setEmail('lukas@becvar.xyz');
        $user->setPassword($this->securityUtil->generateHash('test'));
        $user->setRoles(['ROLE_USER']);
        $user->setApiToken(ByteString::fromRandom(32)->toString());
        $user->setIpAddress('127.0.0.1');

        // set register and last login time
        $user->setRegisterTime(new DateTime());
        $user->setLastLoginTime(new DateTime());

        // persist user
        $manager->persist($user);

        // flush data
        $manager->flush();
    }
}

<?php

namespace App\Manager;

use App\Entity\User;
use App\Util\SecurityUtil;
use App\Util\VisitorInfoUtil;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\ByteString;

/**
 * Class UserManager
 *
 * Service class for managing user-related operations.
 *
 * @package App\Manager
 */
class UserManager
{
    private LogManager $logManager;
    private SecurityUtil $securityUtil;
    private ErrorManager $errorManager;
    private VisitorInfoUtil $visitorInfoUtil;
    private EntityManagerInterface $entityManager;

    public function __construct(LogManager $logManager, SecurityUtil $securityUtil, VisitorInfoUtil $visitorInfoUtil, ErrorManager $errorManager, EntityManagerInterface $entityManager)
    {
        $this->logManager = $logManager;
        $this->securityUtil = $securityUtil;
        $this->errorManager = $errorManager;
        $this->entityManager = $entityManager;
        $this->visitorInfoUtil = $visitorInfoUtil;
    }

    /**
     * Retrieve a user from the repository based on search criteria.
     *
     * @param array<mixed> $search The search criteria.
     *
     * @return User|null The user object if found, null otherwise.
     */
    public function getUserRepo(array $search): ?User
    {
        // get user repo
        return $this->entityManager->getRepository(User::class)->findOneBy($search);
    }

    /**
     * Register a new user.
     *
     * @param string $email The email of the new user.
     * @param string $password The password of the new user.
     *
     * @return void
     */
    public function registerUser(string $email, string $password): void
    {
        // generate entity token
        $token = ByteString::fromRandom(32)->toString();

        // check if token not exist
        if ($this->getUserRepo(['api_token' => $token]) != null) {
            $this->registerUser($email, $password);
        }

        // hash password
        $password = $this->securityUtil->generateHash($password);

        // check if user exist
        if ($this->getUserRepo(['email' => $email]) == null) {
            try {
                // init user entity
                $user = new User();

                // set user data
                $user->setEmail($email);
                $user->setPassword($password);
                $user->setRoles(['ROLE_USER']);
                $user->setApiToken($token);
                $user->setIpAddress($this->visitorInfoUtil->getIP());

                // set register and last login time
                $user->setRegisterTime(new DateTime());
                $user->setLastLoginTime(new DateTime());

                // flush user to database
                $this->entityManager->persist($user);
                $this->entityManager->flush();

                // log action
                $this->logManager->log('authenticator', 'new registration user: ' . $email);
            } catch (Exception $e) {
                $this->errorManager->handleError('error to register new user: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    /**
     * Checks if the specified user has the admin role.
     *
     * @param string $email The email of the user to check
     *
     * @return bool True if the user has the admin role, otherwise false
     */
    public function isUserAdmin(string $email): bool
    {
        $user = $this->getUserRepo(['email' => $email]);

        if ($user !== null) {
            $roles = $user->getRoles();
            return in_array('ROLE_ADMIN', $roles);
        }

        return false;
    }

    /**
     * Adds the admin role to a user.
     *
     * @param string $email The email of the user to add the admin role to
     *
     * @return void
     *
     * @throws Exception If there is an error while adding the admin role
     */
    public function addAdminRoleToUser(string $email): void
    {
        // get user repo
        $repo = $this->getUserRepo(['email' => $email]);

        // check if user exist
        if ($repo != null) {
            try {
                // update role
                $repo->setRoles(['ROLE_ADMIN']);

                // flush updated user data
                $this->entityManager->flush();

                // log action
                $this->logManager->log('role-granted', 'role admin granted to user: ' . $email);
            } catch (Exception $e) {
                $this->errorManager->handleError('error to grant admin permissions: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }
}

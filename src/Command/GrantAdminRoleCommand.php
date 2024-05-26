<?php

namespace App\Command;

use App\Manager\UserManager;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GrantAdminRoleCommand
 *
 * Command to grant admin role to a user.
 *
 * @package App\Command
 */
#[AsCommand(name: 'sn:user:grant-admin', description: 'Grant admin role to user')]
class GrantAdminRoleCommand extends Command
{
    private UserManager $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
        parent::__construct();
    }

    /**
     * Configures the command arguments.
     */
    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::OPTIONAL, 'New admin user email');
    }

    /**
     * Executes the command to grant admin role to a user.
     *
     * @param InputInterface $input The input interface
     * @param OutputInterface $output The output interface
     *
     * @return int The status code
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // fix get CLI ip address
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';

        // get email argument
        $email = $input->getArgument('email');

        // check if email is added
        if ($email == null) {
            $io->error('You must add the admin email argument!');
            return Command::FAILURE;
        }

        // check if email is used
        if ($this->userManager->getUserRepo(['email' => $email]) == null) {
            $io->error('Error email: ' . $email . ' is not registered!');
            return Command::FAILURE;
        }

        try {
            // check if user is admin
            if ($this->userManager->isUserAdmin($email)) {
                $io->error('User: ' . $email . ' is already admin');
                return Command::FAILURE;
            } else {
                // grant role to user
                $this->userManager->addAdminRoleToUser($email);

                // return success message
                $io->success('admin role granted to: ' . $email);
                return Command::SUCCESS;
            }
        } catch (Exception $e) {
            $io->success('error to grant admin: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}

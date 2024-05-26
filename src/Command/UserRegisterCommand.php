<?php

namespace App\Command;

use App\Manager\UserManager;
use Symfony\Component\String\ByteString;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UserRegisterCommand
 *
 * Command to register a new user.
 *
 * @package App\Command
 */
#[AsCommand(name: 'sn:user:register', description: 'Register new user')]
class UserRegisterCommand extends Command
{
    private UserManager $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
        parent::__construct();
    }

    /**
     * Configures the current command.
     *
     * This method is responsible for configuring the command by defining its name, description, and any arguments or options it accepts.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::OPTIONAL, 'New user email');
    }

    /**
     * Executes the command to register a new user.
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
            $io->error('You must add the new email argument!');
            return Command::FAILURE;
        }

        // validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $io->error('The email address format is invalid!');
            return Command::FAILURE;
        }

        // check if email is used
        if ($this->userManager->getUserRepo(['email' => $email]) != null) {
            $io->error('Error email: ' . $email . ' is already used!');
            return Command::FAILURE;
        }

        try {
            // generate user password
            $password = ByteString::fromRandom(32)->toString();

            // register user
            $this->userManager->registerUser($email, $password);

            // return success message
            $io->success('New user registered email: ' . $email . ' with password: ' . $password);
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->success('error to register user: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}

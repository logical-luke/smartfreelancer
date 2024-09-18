<?php

namespace App\Command;

use App\Service\User\Creator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-user',
    description: 'Add a short description for your command',
)]
class CreateUserCommand extends Command
{
    public function __construct(private readonly Creator $userCreator)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Email for user')
            ->addArgument('password', InputArgument::REQUIRED, 'Password for user')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if ($email = $input->getArgument('email')) {
            $io->note(sprintf('Creating a user with email: %s', $email));
        }

        try {
            ($this->userCreator)($email, $input->getArgument('password'));
        } catch (\RuntimeException $e) {
            $io->error(sprintf('User creation failed due to an error: %s', $e->getMessage()));

            return Command::FAILURE;
        }

        $io->success(sprintf('Created new user with email: %s', $email));

        return Command::SUCCESS;
    }
}

<?php

namespace App\Command;

use App\Entity\Client;
use App\Entity\Project;
use App\Entity\Task;
use App\Repository\ClientRepository;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-content-for-user',
    description: 'Add a short description for your command',
)]
class CreateContentForUserCommand extends Command
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly TaskRepository $taskRepository,
        private readonly ProjectRepository $projectRepository,
        private readonly ClientRepository $clientRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Argument description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $userEmail = $input->getArgument('email');

        if (!$user = $this->userRepository->findOneBy(['email' => $userEmail])) {
            $io->error('User not found');

            return Command::FAILURE;
        }

        for ($i = 0; $i < 200; ++$i) {
            $task = Task::fromUser($user);
            $task->setName((string) random_int(0, 200000));

            $this->taskRepository->save($task);
        }

        $this->taskRepository->save($task, true);

        for ($i = 0; $i < 200; ++$i) {
            $project = Project::fromUser($user);
            $project->setName((string) random_int(0, 200000));

            $this->projectRepository->save($project);
        }

        $this->projectRepository->save($project, true);

        for ($i = 0; $i < 200; ++$i) {
            $client = Client::fromUser($user);
            $client->setName((string) random_int(0, 200000));

            $this->clientRepository->save($client);
        }

        $this->clientRepository->save($client, true);

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}

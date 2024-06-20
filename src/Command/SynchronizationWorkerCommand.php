<?php

namespace App\Command;

use App\Model\Client\CreateClientPayload;
use App\Model\Synchronization\ActionPayloadInterface;
use App\Model\Synchronization\DataInterface;
use App\Model\Synchronization\Payload;
use App\Service\Synchronization\Consumer;
use App\Service\Synchronization\Handler;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV7;
use Throwable;

#[AsCommand(
    name: 'app:synchronization-worker',
    description: 'Add a short description for your command',
)]
class SynchronizationWorkerCommand extends Command
{
    public function __construct(
        private readonly Consumer $consumer,
        private readonly Handler $handler,
        private readonly ManagerRegistry $registry,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->consumer->consumeMessages('synchronization', function (AMQPMessage $message) use ($io) {
                try {
                    ($this->handler)(
                        Payload::fromJson($message->getBody()),
                    );
                    $io->success(sprintf('Processed message: %s', $message->getBody()));
                } catch (Throwable|Exception $e) {
                    $io->error(sprintf('Failed to process message: %s due to %s', $message->getBody(), $e->getMessage()));
                    $this->registry->resetManager();
                    sleep(1);
                }
        });

        return Command::SUCCESS;
    }
}

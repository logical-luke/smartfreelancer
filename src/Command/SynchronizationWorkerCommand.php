<?php

namespace App\Command;

use App\Model\Synchronization\SynchronizationPayload;
use App\Service\Synchronization\RabbitMQConsumer;
use App\Service\Synchronization\SynchronisationProcessor;
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

#[AsCommand(
    name: 'app:synchronization-worker',
    description: 'Add a short description for your command',
)]
class SynchronizationWorkerCommand extends Command
{

    public function __construct(
        private  readonly RabbitMQConsumer $consumer,
        private readonly SynchronisationProcessor $processor,
        private readonly ManagerRegistry $registry,
    ) {
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->consumer->consumeMessages('synchronization', function (AMQPMessage $message) use ($io) {
            try {
                try {
                    ($this->processor)(SynchronizationPayload::from(json_decode($message->getBody(), true, 512, JSON_THROW_ON_ERROR)));
                } catch (Exception $e) {
                    $io->error($e->getMessage());
                    $this->registry->resetManager();
                    sleep(1);
                } catch (\Throwable $e) {
                    $io->error($e->getMessage());
                }
                $io->success('Processed message' . $message->getBody());
            } catch (\JsonException $e) {
                $io->error('Failed to process message' . $message->getBody());
            }
        });

        return Command::SUCCESS;
    }

    private function processMessage(string $message): void
    {

    }

}

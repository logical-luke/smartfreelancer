<?php

declare(strict_types=1);

namespace App\Service\Synchronization;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Producer
{
    private AMQPStreamConnection $connection;

    public function __construct(string $host, string $port, string $user, string $password, string $vhost)
    {
        $this->connection = new AMQPStreamConnection($host, (int)$port, $user, $password, $vhost, $vhost);
    }

    public function publishMessage(string $message, string $queueName): void
    {
        $channel = $this->connection->channel();
        $channel->queue_declare($queueName, false, true, false, false);
        $channel->basic_publish(new AMQPMessage($message), '', $queueName);
        $channel->close();
    }
}

<?php

declare(strict_types=1);

namespace App\Service\Synchronization;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQConsumer
{
    private AMQPStreamConnection $connection;

    public function __construct(string $host, string $port, string $user, string $password, string $vhost)
    {
        $this->connection = new AMQPStreamConnection($host, (int)$port, $user, $password, $vhost);
    }

    public function consumeMessages(string $queueName, callable $callback): void
    {
        $channel = $this->connection->channel();
        $channel->queue_declare($queueName, false, true, false, false);

        $channel->basic_consume($queueName, '', false, true, false, false, $callback);

        while ($channel->is_consuming()) {
            $channel->wait();
        }
    }
}

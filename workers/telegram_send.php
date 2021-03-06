<?php

use MaCroTux\HttpUtils\CurlHttpSenderService;
use MaCroTux\Telegram\Config;
use MaCroTux\Telegram\SendMessageService;

require_once __DIR__ . '/../vendor/autoload.php';

const RABBITMQ_HOST = "rabbit-manager";
const RABBITMQ_PORT = 5672;
const RABBITMQ_USERNAME = "guest";
const RABBITMQ_PASSWORD = "guest";
const RABBITMQ_QUEUE_NAME = "telegram_message";

$connection = new \PhpAmqpLib\Connection\AMQPStreamConnection(
    HOST,
    PORT,
    USERNAME,
    PASSWORD
);

$channel = $connection->channel();

# Create the queue if it doesnt already exist.
$channel->queue_declare(
    $queue = QUEUE_NAME,
    $passive = false,
    $durable = true,
    $exclusive = false,
    $auto_delete = false,
    $nowait = false,
    $arguments = null,
    $ticket = null
);


echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

$callback = function ($msg) {
    echo " [x] Received ", $msg->body, "\n";

    $curlHttpSenderService = new CurlHttpSenderService();
    $telegramConfig = new Config(
        'XXXXXXXXXXX',
        '000000000000'
    );

    $sendMessageService = new SendMessageService(
        $telegramConfig,
        $curlHttpSenderService
    );

    $job = json_decode($msg->body, true);

    $sendMessageService->__invoke($job['message'] ?? 'No message!');


    sleep($job['sleep_period']);

    echo " [x] Done", "\n";

    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
};

$channel->basic_qos(null, 1, null);

$channel->basic_consume(
    $queue = QUEUE_NAME,
    $consumer_tag = '',
    $no_local = false,
    $no_ack = false,
    $exclusive = false,
    $nowait = false,
    $callback
);

while (count($channel->callbacks))
{
    $channel->wait();
}

$channel->close();
$connection->close();
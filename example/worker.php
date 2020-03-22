<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/config.php';

$connection = new AMQPStreamConnection(HOST, PORT, USERNAME, PASSWORD);

$channel = $connection->channel();

# Create the queue if it doesnt already exist.
$channel->queue_declare('task_queue', false, true, false, false);

$callback = static function($msg) {
    echo " [x] Received ", $msg->body, "\n";
    $job = json_decode($msg->body, true);
    sleep($job['sleep_period']);
    echo " [x] Done", "\n";

    /** @var PhpAmqpLib\Channel\AMQPChannel $channel */
    $channel = $msg->delivery_info['channel'];
    $channel->basic_ack($msg->delivery_info['delivery_tag']);
};

$channel->basic_qos(null, 1, null);
$channel->basic_consume(QUEUE_NAME, '', false, false, false, false, $callback);

echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

while (count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();
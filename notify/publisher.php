<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/config.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection(HOST, PORT, USERNAME, PASSWORD);

$channel = $connection->channel();

$channel->queue_declare('task_queue', false, true, false, false);

$job_id = 0;

while (true) {
    $jobArray = [
        'id' => uniqid(),
        'job_number' => $job_id++,
        'task' => 'notify',
        'message' => 'Esto es un test',
    ];

    $data = json_encode($jobArray, JSON_UNESCAPED_SLASHES);

    $msg = new AMQPMessage(
        $data,
        [
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ]
    );

    $channel->basic_publish($msg, '', QUEUE_NAME);
    print 'Job created: ' . $job_id . PHP_EOL;

    echo ' [x] Sent Job ' . $job_id .' : '.  $data . "\n";

    sleep(1);
}

$channel->close();
$connection->close();
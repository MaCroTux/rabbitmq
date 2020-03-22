<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/example/config.php';

$connection = new \PhpAmqpLib\Connection\AMQPStreamConnection(
    HOST,
    PORT,
    USERNAME,
    PASSWORD
);

$channel = $connection->channel();

# Create the queue if it does not already exist.
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

$job_id = 0;

$jobArray = array(
    'id' => uniqid(),
    'message' => $_GET['q'],
    'sleep_period' => rand(0, 3)
);

$msg = new \PhpAmqpLib\Message\AMQPMessage(
    json_encode($jobArray, JSON_UNESCAPED_SLASHES),
    array('delivery_mode' => 2) # make message persistent
);

$channel->basic_publish($msg, '', QUEUE_NAME);

print 'Job created' . PHP_EOL;
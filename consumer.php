<?php

require_once __DIR__ . '\vendor\autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$host = 'localhost';
$port = 5672;
$user = 'guest';
$pass = 'guest';
$queue = 'msgs';

$connection = new AMQPStreamConnection($host, $port, $user, $pass);
$channel = $connection->channel();

$channel->queue_declare($queue, false, true, false, false);

echo '[*] Waiting for messages. To exit press CTRL+C', '\n';
$callback = function ($msgs) {
    echo "[x] Received ", $msgs->body, "\n";
};
$channel->basic_consume('TestQueue', '', false, true, false, false, $callback);
while (count($channel->$callback)) {
    $channel->wait();
};

$channel->close();
$connection->close();

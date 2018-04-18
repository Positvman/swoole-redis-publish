<?php

require_once 'redis.php';
require_once 'lib/WebSocketClient.php';

$channels = ['tangce'];

function subscriber(Redis $redis, $channel, $message)
{
     if (null === $data = @json_decode($message, true)) {
         return;
     }

    switch ($channel) {
        case 'tangce':
            $client = new WebSocketClient('127.0.0.1', 9501);
            if (@$client->connect()) {
                $client->send($message);
            }
            break;
    }
}

try {
    $redis->subscribe($channels, 'subscriber');
} catch (\RedisException $e) { }

<?php

if (!isset($_GET['uid'])) {
    exit('Invalid Query Params "uid"');
}

$redis = new Redis;
$redis->connect('127.0.0.1', 6379);

$message = [
    'uid'   => $_GET['uid'],
    'type'  => 'push',
    'msg'   => 'Hello World'
];

$redis->publish('tangce', json_encode($message, JSON_UNESCAPED_UNICODE));

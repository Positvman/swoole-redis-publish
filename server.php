<?php

require_once 'redis.php';

/**
 * \swoole_websocket_server
 */
$server = new swoole_websocket_server('0.0.0.0', 9501);
$server->set([
    // 'daemonize'     => 1,
    'worker_num'    => 4,
    'reactor_num'   => 2
]);

/**
 * onopen
 */
// $server->on('open', function(swoole_websocket_server $server, $request) {
//     echo "server: handshake success with fd ({$request->fd})\n";
// });

/**
 * onmessage
 */
$server->on('message', function(swoole_websocket_server $server, $frame) use ($redis) {
    // echo "message: {$frame->data}\n";
    // 解析 message 数据
    if (null === $data = @json_decode($frame->data, true)) {
        return;
    }
    // 第N个 message 必需指定 uid
    if (!isset($data['uid'])) {
        return;
    }
    // 获取 uid 与 fds 映射关系
    $fds = $redis->sMembers($data['uid']);
    switch ($data['type']) {
        case 'bind':
            $redis->sAdd($data['uid'], $frame->fd);
            break;
        case 'push':
            print_r($fds);
            foreach ($fds as $fd) {
                if ($server->exist($fd)) {
                    $server->push($fd, $frame->data);
                }
            }
            break;
    }
});

/**
 * onclose
 */
 $server->on('close', function(swoole_websocket_server $server, $fd) {
     echo "client {$fd} closed\n";
 });

/**
 * start
 */
$server->start();

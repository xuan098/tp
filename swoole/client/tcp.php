<?php
/**
 * Create by
 * User: xuan098
 * Date: 2022/3/29
 * Time: 15:18
 * File: tcp.php
 */

use Swoole\Coroutine\Client;
use function Swoole\Coroutine\run;

run(function () {
    $client = new Client(SWOOLE_SOCK_TCP);
    if (!$client->connect('127.0.0.1', 9501, 0.5)) {
        echo "链接失败. Error: {$client->errCode}\n";
    }
    fwrite(STDOUT, "请输入: ");
    $res = fgets(STDIN);
    $client->send("{$res}\n");
    echo $client->recv();
    $client->close();
});


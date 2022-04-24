<?php

/**
 * Create by
 * User: xuan098
 * Date: 2022/3/29
 * Time: 15:06
 * File: tcp.php
 */
class udp
{

    public $server;

    public function __construct()
    {
        $this->server = new Swoole\Server('127.0.0.1', 9502, SWOOLE_PROCESS, SWOOLE_SOCK_UDP);
        $this->server->set([
            'worker_num' => 4,
            'max_request' => 50,
        ]);
        $this->server->on('Packet', [$this, "onPacket"]);
        //启动服务器
        $this->server->start();
    }


    public function onPacket($server, $data, $clientInfo)
    {
        var_dump($clientInfo);
        $server->sendto($clientInfo['address'], $clientInfo['port'], "发送的数据：{$data}");
    }


}

new udp();
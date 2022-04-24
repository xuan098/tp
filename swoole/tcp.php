<?php

/**
 * Create by
 * User: xuan098
 * Date: 2022/3/29
 * Time: 15:06
 * File: tcp.php
 */
class tcp
{

    public $server;

    public function __construct()
    {
        $this->server = new Swoole\Server('127.0.0.1', 9501);
        $this->server->set([
            'worker_num' => 4,
            'max_request' => 50,
        ]);
        $this->server->on('Connect', [$this, "onConnect"]);
        $this->server->on('Receive', [$this, "onReceive"]);
        $this->server->on('Close', [$this, "onClose"]);
        //启动服务器
        $this->server->start();
    }


    public function onConnect($server, $fd)
    {
        echo "客户端ID：{$fd}，链接.\n";
    }

    public function onReceive($server, $fd, $reactor_id, $data)
    {
        $server->send($fd, "发送的数据: {$data}");
    }

    public function onClose($server, $fd)
    {
        echo "客户端ID：{$fd}，关闭.\n";
    }

}

new tcp();
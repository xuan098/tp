<?php

/**
 * Create by
 * User: xuan098
 * Date: 2022/3/29
 * Time: 15:06
 * File: tcp.php
 */
class ws
{

    public $ws;

    public function __construct()
    {
        $this->ws = new Swoole\WebSocket\Server('0.0.0.0', 9504, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);
        $this->ws->set([
            'task_worker_num' => 4,
            'enable_static_handler' => true,
            'document_root' => '/www/wwwroot/tp/swoole/'
        ]);
        $this->ws->on('Connect', [$this, "onConnect"]);
        $this->ws->on('Open', [$this, "onOpen"]);
        $this->ws->on('Message', [$this, "onMessage"]);
        $this->ws->on('Task', [$this, "onTask"]);
        $this->ws->on('Finish', [$this, "onFinish"]);
        $this->ws->on('Close', [$this, "onClose"]);
        //启动服务器
        $this->ws->start();
    }

    public function onConnect($ws, $fd)
    {
        echo "客户端ID：{$fd}，链接.\n";
    }

    //监听WebSocket连接打开事件
    public function onOpen($ws, $request)
    {
        $ws->push($request->fd, "欢迎客户端：{$request->fd}\n");
    }

    //监听WebSocket消息事件
    public function onMessage($ws, $frame)
    {
        echo "信息: {$frame->data}\n";
        foreach ($ws->connections as $fd) {
            if ($fd == $frame->fd) {
                $ws->push($fd, "我: {$frame->data}");
            } else {
                $ws->push($fd, "对方: {$frame->data}");
            }
            $taskData['fd'] = $fd;
            $taskData['message'] = "我: {$frame->data}";
            $ws->Task($taskData);
        }

    }

    //处理异步任务(此回调函数在task进程中执行)
    public function onTask($ws, $task_id, $src_worker_id, $data)
    {
        sleep(5);
        return $data;
    }

    //处理异步任务的结果
    public function onFinish($ws, $task_id, $data)
    {
        var_dump('task_id:', $task_id);
        var_dump($data);
    }


    //监听WebSocket连接关闭事件
    public function onClose($ws, $fd)
    {
        echo "客户端：{$fd} 关闭\n";
    }

}

new ws();
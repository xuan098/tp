<?php

/**
 * Create by
 * User: xuan098
 * Date: 2022/3/29
 * Time: 15:06
 * File: tcp.php
 */
class http
{

    public $http;

    public function __construct()
    {
        $this->http = new Swoole\Http\Server('0.0.0.0', 9501);
        $this->http->set([
            'enable_static_handler' => true,
            'document_root' => '/www/wwwroot/tp/swoole/static',
        ]);
        $this->http->on('Request', [$this, "onRequest"]);
        //启动服务器
        $this->http->start();
    }


    public function onRequest($request, $response)
    {
        $response->header('Content-Type', 'text/html; charset=utf-8');
        $response->end('<h1>Hello Swoole. #' . json_encode($request->get) . '</h1>');
    }

}

new http();
<?php
/**
 * Create by
 * User: xuan098
 * Date: 2022/4/12
 * Time: 14:45
 * File: Chat.php
 */

namespace app\api\controller;

use app\BaseController;
use WebSocket\Client;

class Chat extends BaseController
{
    public function test()
    {
        $client = new Client('ws://82.156.178.191:9504?token=' . $this->getToken());
        $client->send('是的');
        var_dump($client->receive());
        $client->close();

    }

}
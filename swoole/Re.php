<?php
/**
 * Create by
 * User: xuan098
 * Date: 2022/4/2
 * Time: 10:33
 * File: MySQL.php
 */

use Swoole\Runtime;
use function Swoole\Coroutine\run;

class Re
{
    public function index()
    {
        // 此行代码后，文件操作，sleep，Mysqli，PDO，streams等都变成异步IO，见'一键协程化'章节
        Runtime::enableCoroutine();
        $s = microtime(true);
        // Swoole\Coroutine\run()见'协程容器'章节
        run(function () {
            go(function () {
                for ($i = 0; $i < 100; $i ++) {
                    $redis = new Redis();
                    $redis->connect('127.0.0.1', 6379);//此处产生协程调度，cpu切到下一个协程，不会阻塞进程
                    $res = $redis->get('ceshi');//此处产生协程调度，cpu切到下一个协程，不会阻塞进程
                    echo "A: {$res}。\n";
                }
            });

            go(function () {
                for ($i = 0; $i < 100; $i ++) {
                    $redis = new Redis();
                    $redis->connect('127.0.0.1', 6379);//此处产生协程调度，cpu切到下一个协程，不会阻塞进程
                    $res = $redis->get('ceshi1');//此处产生协程调度，cpu切到下一个协程，不会阻塞进程
                    echo "B: {$res}。\n";
                }
            });

        });
        $time = (microtime(true) - $s);
        echo "用时: {$time}s。\n";
    }
}

$redis = new Re();
$redis->index();
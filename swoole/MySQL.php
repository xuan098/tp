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

class MySQL
{
    public function go()
    {
        // 此行代码后，文件操作，sleep，Mysqli，PDO，streams等都变成异步IO，见'一键协程化'章节
        Runtime::enableCoroutine();
        $s = microtime(true);
        // Swoole\Coroutine\run()见'协程容器'章节
        run(function () {
            // 10k pdo and mysqli read
            /*for ($c = 50; $c --;) {
                go(function () {
                    $pdo = new PDO('mysql:host=127.0.0.1;dbname=ceshi;charset=utf8', 'ceshi', '7f6CZZxfwY5h3cf2');
                    $statement = $pdo->prepare('SELECT * FROM  `user`');
                    for ($n = 100; $n --;) {
                        $statement->execute();
                        $res = $statement->fetch();
                        var_dump('测试A=>用户名：'.$res['nick_name']);
                    }
                });
            }*/
            /* for ($c = 50; $c --;) {*/
            go(function () {
                $mysqli = mysqli_connect('127.0.0.1', 'ceshi', '7f6CZZxfwY5h3cf2', 'ceshi');
                /*for ($n = 100; $n --;) {*/
                $temp = mysqli_query($mysqli, 'SELECT id FROM  `fang_user`');
                $res = mysqli_fetch_array($temp);
                foreach ($res as $key => $value) {
                    var_dump('测试B=>用户名：' . $value['id']);
                }

                /*}*/
            });
            /*}*/
        });
        $time = (microtime(true) - $s);
        echo "用时: {$time}s。\n";
    }
}

$mysql = new MySQL();
$mysql->go();
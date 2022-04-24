<?php
/**
 * Create by
 * User: xuan098
 * Date: 2022/4/11
 * Time: 14:32
 * File: User.php
 */

namespace app\common\bussiness\api;

use app\common\bussiness\lib\Redis;
use app\common\bussiness\lib\Str;
use app\common\model\api\Friend as FriendModel;
use app\common\model\api\User as UserModel;
use Exception;
use think\facade\Db;
use WebSocket\Client;

class User
{
    private $userModel = null;
    private $friendModel = null;
    private $str = null;
    private $redis = null;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->friendModel = new FriendModel();
        $this->str = new Str();
        $this->redis = new Redis();
    }

    public function userData($uid)
    {
        return $this->userModel->findByUid($uid);
    }

    /**
     * @throws Exception
     */
    public function login($data)
    {
        $isExist = $this->userModel->findByUsernameWithStatus($data['username']);
        if (empty($isExist)) {
            throw new Exception('用户不存在!');
        }
        $password = md5($isExist['password_salt'] . $data['password'] . $isExist['password_salt']);
        if ($password != $isExist['password']) {
            throw new Exception('密码错误!');
        }
        $this->redis->delete(config('redis.token_pre') . $isExist['last_login_token']);
        $token = $this->str->createToken($isExist['username']);

        $updateData['username'] = $isExist['username'];
        $updateData['last_login_token'] = $token;
        $this->userModel->updateLoginInfo($updateData);

        $userData['id'] = $isExist['id'];
        $userData['username'] = $isExist['username'];
        $this->redis->set(config('redis.token_pre') . $token, $userData);
        return $token;
    }


    /**
     * @throws Exception
     */
    public function register($data)
    {
        $isExist = $this->userModel->findByUsername($data['username']);
        if (!empty($isExist)) {
            throw new Exception('用户已经被注册');
        }
        $data['password_salt'] = $this->str->salt(5);
        $data['password'] = md5($data['password_salt'] . $data['password'] . $data['password_salt']);
        $this->userModel->save($data);
    }

    public function logoff($token)
    {
        $this->redis->delete(config('redis.token_pre') . $token);
    }

    /**
     * @throws \WebSocket\BadOpcodeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws Exception
     */
    public function addFriend($data)
    {
        $isExist = $this->userModel->findByUsernameWithStatus($data['username']);
        if (empty($isExist)) {
            throw new Exception('请求的用户不存在!');
        }
        $socket = $this->redis->get(config('redis.socket_pre') . $isExist['id']);
        if (!empty($socket['apply_list'])) {
            foreach ($socket['apply_list'] as $key => $value) {
                if ($key == $data['user']['id']) {
                    throw new Exception('请勿重新申请!');
                }
            }
        }
        if ($this->friendModel->isFriend($data['user']['id'], $isExist['id'])) {
            throw new Exception('已成为好友!');
        }
        if ($isExist['id'] == $data['user']['id']) {
            throw new Exception('不能加自己为好友!');
        }

        $send['type'] = 'addFriend';
        $send['uid'] = $data['user']['id'];
        $send['username'] = $data['user']['username'];
        $send['target'] = $isExist['id'];//申请的好友的ID
        $send['message'] = $data['message'];


        $client = new Client('ws://82.156.178.191:9504?type=public&token=' . $data['token']);
        $client->send(json_encode($send));
        $receive = json_decode($client->receive(), true);
        if ($receive['status'] == config('status.success')) {
            $client->close();
        }

    }

    public function handleFriend($data)
    {
        $socket = $this->redis->get(config('redis.socket_pre') . $data['uid']);
        if (empty($socket['apply_list']) || !array_key_exists($data['target'], $socket['apply_list'])) {
            throw new Exception('好友申请不存在!');
        }
        // 启动事务
        Db::startTrans();
        try {
            $this->redis->multi();
            if ((boolean)$data['decision']) {
                $list = [
                    [
                        'uid' => $data['uid'],
                        'fid' => $data['target']
                    ], [
                        'fid' => $data['uid'],
                        'uid' => $data['target'],
                    ]
                ];
                $this->friendModel->saveAll($list);
            }
            unset($socket['apply_list'][$data['target']]);
            $this->redis->rset(config('redis.socket_pre') . $data['uid'], $socket);
            $this->redis->exec();
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            $this->redis->discard();
            // 回滚事务
            Db::rollback();
            throw new Exception('请求出错！！');
        }

    }

    public function friendList($uid)
    {
        return $this->friendModel->friendList($uid);
    }

}
<?php
/**
 * Create by
 * User: xuan098
 * Date: 2022/4/11
 * Time: 15:13
 * File: User.php
 */

namespace app\common\model\api;

use think\Model;

class User extends Model
{
    protected $table = 'api_user';

    public function findByUid($uid)
    {
        return $this->where('id', $uid)->find();
    }

    public function findByUsername($username)
    {
        return $this->where('username', $username)
            ->field('id,username')
            ->find();
    }

    public function findByUsernameWithStatus($username)
    {
        return $this->where('username', $username)->where('status', 1)->find();
    }

    public function updateLoginInfo($data)
    {
        $result = $this->findByUsernameWithStatus($data['username']);
        $saveData['last_login_token'] = $data['last_login_token'];
        //$saveData['username'] = $result['username'];
        return $result->save($saveData);
    }

    public function findByIdWithStatus($id)
    {
        return $this->where('id', $id)->where('status', 1)->find();
    }

}
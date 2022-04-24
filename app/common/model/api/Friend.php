<?php
/**
 * Create by
 * User: xuan098
 * Date: 2022/4/14
 * Time: 10:01
 * File: Friend.php
 */

namespace app\common\model\api;

use think\Model;

class Friend extends Model
{
    protected $table = 'api_friend';
    protected $autoWriteTimestamp = true;

    public function isFriend($uid, $fid)
    {
        $u = $this->where('uid', $uid)->where('fid', $fid)->where('status', 1)->find();
        $f = $this->where('fid', $uid)->where('uid', $fid)->where('status', 1)->find();

        /*if (!empty($u)&&empty($f))
        {
            return true;
        }else{
            return false;
        }*/
        return !empty($u) && !empty($f);
    }

    public function friendList($uid)
    {
        return $this->alias('f')
            ->where('f.uid', $uid)
            ->where('f.status', 1)
            ->join('api_user u', 'f.fid=u.id')
            ->field('u.username,u.id,f.fid')
            ->select();

    }

    /*public function username()
    {
        return $this->belongsTo(User::class, 'fid')->bind(['username']);
    }*/

}
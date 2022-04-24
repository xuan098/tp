<?php
/**
 * Create by
 * User: xuan098
 * Date: 2022/4/11
 * Time: 10:56
 * File: Str.php
 */

namespace app\common\bussiness\lib;

class Str
{
    /**
     * 生成token
     *
     * @param $str
     *
     * @return string
     */
    public function createToken($str): string
    {
        $token = md5(uniqid(md5(microtime(true)), true));
        return sha1($token . $str);
    }

    /**
     * 生成盐
     *
     * @param $bit
     *
     * @return string
     */
    public function salt($bit): string
    {
        //盐字符集
        $chars = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIIOPASDFGHJKLZXCVBNM0123456789';
        $str = '';
        for ($i = 0; $i < $bit; $i ++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

}
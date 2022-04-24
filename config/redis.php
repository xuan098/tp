<?php
/**
 * Create by
 * User: xuan098
 * Date: 2022/4/11
 * Time: 11:07
 * File: redis.php
 */

return [

    //激活token
    "active_token" => "active_account_pre_",
    //登录token
    "token_pre" => "access_token_pre_",
    //登录token持续时间
    "token_expire" => 24 * 3600,
    //登录验证码
    "code_pre" => "login_pre_",
    //登录验证码过期时间
    "code_expire" => 120,
    //文件数据过期时间
    "file_expire" => 3600 / 4,

    //ws
    'socket_pre' => "socket_uid_",
    'socket_add' => "socket_fid_add_",
];

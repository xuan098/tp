<?php
/**
 * Create by
 * User: xuan098
 * Date: 2022/4/11
 * Time: 14:35
 * File: User.php
 */

namespace app\common\validate\api;

use think\Validate;

class User extends Validate
{
    protected $rule = [
        'username|用户名' => ['require', 'max' => 20, 'min' => 2],
        'password|密码' => ['require', 'max' => 20, 'min' => 4],
        'message|留言' => ['max' => 20],
        'decision' => ['require', 'between:0,1'],
        'target' => ['require', 'number'],
    ];

    protected $scene = [
        'login_register' => ['username', 'password'],
        'add_friend' => ['username', 'message'],
        'handle_friend' => ['decision', 'target']
    ];

}
<?php
/**
 * Create by
 * User: xuan098
 * Date: 2022/4/11
 * Time: 14:26
 * File: user.php
 */

use app\api\middleware\IsLogin;
use think\facade\Route;

Route::group('view/user', function () {
    Route::rule('register', '/api/View/register', 'GET');
    Route::rule('login', '/api/View/login', 'GET');
    Route::rule('index', '/api/View/index', 'GET');
});

Route::group('user', function () {
    Route::rule('register', '/api/User/register', 'POST');
    Route::rule('login', '/api/User/login', 'GET');
});

Route::group('user', function () {
    Route::rule('logoff', '/api/User/logoff', 'GET');
    Route::rule('islogin', '/api/User/isLogin', 'GET');
    Route::rule('addfriend', '/api/User/addFriend', 'POST');
    Route::rule('handlefriend', '/api/User/handleFriend', 'POST');
    Route::rule('friendlist', '/api/User/friendList', 'POST');
})->middleware(IsLogin::class);

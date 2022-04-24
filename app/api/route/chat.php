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

Route::group('view/chat', function () {
    Route::rule('room', '/api/View/chatRoom', 'GET');
});

Route::group('chat', function () {
    Route::rule('test', '/api/Chat/test', 'GET');
})->middleware(IsLogin::class);

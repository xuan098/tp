<?php
/**
 * Create by
 * User: xuan098
 * Date: 2022/4/12
 * Time: 10:25
 * File: VIew.php
 */

namespace app\api\controller;

use app\BaseController;
use think\App;
use think\facade\View as V;
use app\common\bussiness\api\User;

class View extends BaseController
{
    protected $bussiness = null;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->bussiness = new User();
    }

    public function index()
    {
        $token = cookie('api_login_token');
        $userData = $this->getUserData($token);
        V::assign('userData', $userData);
        return V::fetch('index/index');
    }

    public function login()
    {
        return V::fetch('index/login');
    }

    public function register()
    {
        return V::fetch('index/register');
    }

    public function chatRoom()
    {
        return V::fetch('room/index');
    }

}
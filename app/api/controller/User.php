<?php
/**
 * Create by
 * User: xuan098
 * Date: 2022/4/11
 * Time: 14:28
 * File: User.php
 */

namespace app\api\controller;

use app\BaseController;
use app\common\bussiness\api\User as Business;
use app\common\validate\api\User as Validate;
use think\App;

class User extends BaseController
{
    protected $business = null;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->business = new Business();
    }

    /**
     * 用户登录
     *
     * @return \think\response\Json
     * @throws \Exception
     */
    public function login(): \think\response\Json
    {
        $data['username'] = $this->request->param('username', '', 'htmlspecialchars');
        $data['password'] = $this->request->param('password', '', 'htmlspecialchars');
        try {
            validate(Validate::class)->scene('login_register')->check($data);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
        $res = $this->business->login($data);
        return $this->success($res);
    }

    /**
     * 用户注册
     *
     * @return \think\response\Json
     * @throws \Exception
     */
    public function register(): \think\response\Json
    {
        $data['username'] = $this->request->param('username', '', 'htmlspecialchars');
        $data['password'] = $this->request->param('password', '', 'htmlspecialchars');
        try {
            validate(Validate::class)->scene('login_register')->check($data);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
        $this->business->register($data);
        return $this->success('注册成功');
    }

    public function isLogin(): \think\response\Json
    {
        return $this->success('token验证成功');
    }

    /**
     * 退出登录
     *
     * @return \think\response\Json
     */
    public function logoff(): \think\response\Json
    {
        $this->business->logoff($this->getToken());
        return $this->success('退出登录');
    }


    /**
     * 发送添加好友请求
     *
     * @return \think\response\Json
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \WebSocket\BadOpcodeException
     */
    public function addFriend()
    {
        $data['username'] = $this->request->param('username', '', 'htmlspecialchars');
        $data['message'] = $this->request->param('message', '', 'htmlspecialchars');
        $data['user'] = $this->getUser();
        $data['token'] = $this->getToken();
        try {
            validate(Validate::class)->scene('add_friend')->check($data);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
        $this->business->addFriend($data);
        return $this->success('好友申请已发送');

    }

    public function handleFriend()
    {
        $data['decision'] = $this->request->param('decision', '', 'htmlspecialchars');
        $data['target'] = $this->request->param('target', '', 'htmlspecialchars');
        $data['uid'] = $this->getUid();
        /* if (empty($data['target'])){
             return $this->fail($data['target'].$data['decision']);
         }*/
        try {
            validate(Validate::class)->scene('handle_friend')->check($data);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
        $this->business->handleFriend($data);
        return $this->success('好友申请处理完成');
    }

    public function friendList()
    {
        $list = $this->business->friendList($this->getUid());
        return $this->success($list);
    }


}
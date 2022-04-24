<?php
declare (strict_types=1);

namespace app\api\middleware;

use app\BaseController;
use app\common\model\api\User;

class IsLogin extends BaseController
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure $next
     *
     * @return Response|\think\response\Json
     */
    public function handle($request, \Closure $next)
    {
        $token = $this->getToken();
        if (empty($token)) {
            $return['status'] = config('status.goto');
            $return['message'] = config('message.failed');
            $return['result'] = '非法请求';
            return json($return);
        }
        $user = $this->getUser();
        if (empty($user)) {
            $return['status'] = config('status.goto');
            $return['message'] = config('message.failed');
            $return['result'] = '登录过期';
            return json($return);
        }
        $userModel = new User();
        $user = $userModel->findByUsernameWithStatus($user['username']);
        if ($user['last_login_token'] != $token) {
            $return['status'] = config('status.goto');
            $return['message'] = config('message.failed');
            $return['result'] = '异地登录，请重新链接';
            return json($return);
        }
        //
        return $next($request);
    }
}

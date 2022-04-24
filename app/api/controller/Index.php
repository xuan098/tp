<?php
declare (strict_types=1);

namespace app\api\controller;

use app\BaseController;

class Index extends BaseController
{
    public function index(): \think\response\Json
    {
        return $this->success('0001');
    }
}

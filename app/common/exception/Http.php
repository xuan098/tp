<?php
/**
 * Create by
 * User: xuan098
 * Date: 2022/4/11
 * Time: 11:36
 * File: Http.php
 */

namespace app\common\exception;

use think\exception\Handle;
use think\Response;

class Http extends Handle
{
    private $msg = null;
    private $status = null;

    public function render($request, \Throwable $e): Response
    {
        $this->msg = $e->getMessage();
        $this->status = config('status.failed');
        if ($this->msg == config('status.goto')) {
            $this->status = config('status.goto');
        }
        return json(
            [
                'status' => $this->status,
                'message' => $this->msg,
                'result' => null,
            ]
        );
    }

}
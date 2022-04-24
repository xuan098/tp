<?php
// 应用公共文件

function show_res($status, $message, $data, $httpStatus = 200): \think\response\Json
{
    $result = [
        'status' => $status,
        'message' => $message,
        'result' => $data
    ];
    return json($result, $httpStatus);
}

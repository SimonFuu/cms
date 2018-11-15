<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function response($data, $code = 200)
    {
        return response() -> json([
            'data' => $data,
            'code' => $code,
            'result' => $code > 299 ? false : true,
            'message' => '提交成功'
        ]);
    }
}

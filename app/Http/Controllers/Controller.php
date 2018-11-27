<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    const PER_PAGE_RECORD_COUNT = 10;
    const LEADERS_DEP_ID = 13;
    const TOP_MODULE_ID = 1;

    # 如果修改如下列常量的数值，需要同步修改config/app.php Line 236 对应的数值
    const COMMON_MODULE_TYPE = 1;
    const SPECIAL_MODULE_TYPE = 2;
    const VIDEOS_MODULE_TYPE = 3;
    const LINKS_MODULE_TYPE = 4;

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function response($data = [], $code = 200, $message = '提交成功')
    {
        return response() -> json([
            'data' => $data,
            'code' => $code,
            'result' => $code > 299 ? false : true,
            'message' => $message
        ]);
    }
}

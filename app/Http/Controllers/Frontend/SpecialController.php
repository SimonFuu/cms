<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 2018/11/27
 * Time: 9:48 PM
 * https://www.fushupeng.com
 * contact@fushupeng.com
 */

namespace App\Http\Controllers\Frontend;


use Illuminate\Support\Facades\DB;

class SpecialController extends FrontendController
{
    public function list($code = null)
    {
        if (is_null($code)) {
            return abort(404);
        }
        $special = DB::table('modules')
            -> select('id', 'code', 'name')
            -> where('code', $code)
            -> whereNull('deleted_at')
            -> where('type', self::SPECIAL_MODULE_TYPE)
            -> first();
        if (is_null($special)) {
            return abort(404);
        }
        $contents = DB::table('contents')
            -> select('contents.id', 'contents.title', 'contents.created_at', 'contents_modules.is_new')
            -> leftJoin('contents_modules', 'contents_modules.c_id', '=', 'contents.id')
            -> whereNull('contents.deleted_at')
            -> whereNull('contents_modules.deleted_at')
            -> where('contents_modules.m_id', $special -> id)
            -> paginate(self::PER_PAGE_RECORD_COUNT);
        return view('frontend.default.special.list', ['contents' => $contents, 'special' => $special]);
    }

    public function show($code = null, $id = null)
    {

        if (is_null($code) || is_null($id)) {
            return abort(404);
        }
        $module = DB::table('modules')
            -> select('name')
            -> whereNull('deleted_at')
            -> where('code', $code)
            -> first();

        if (is_null($module)) {
            return abort(404);
        }

        $content = DB::table('contents')
            -> select('title', 'source', 'content', 'created_at')
            -> whereNull('deleted_at')
            -> where('id', $id)
            -> first();
        if (is_null($content)) {
            abort(404);
        }
        return view('frontend.default.special.show',
            ['content' => $content, 'special' => $module -> name, 'code' => $code]);
    }
}
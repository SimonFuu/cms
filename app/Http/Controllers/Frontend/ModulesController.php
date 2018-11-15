<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 2018/11/10
 * Time: 8:47 PM
 * https://www.fushupeng.com
 * contact@fushupeng.com
 */

namespace App\Http\Controllers\Frontend;


use Illuminate\Support\Facades\DB;

class ModulesController
{
    public function list($module_code)
    {
        if ($module_code == config('app.top_news.code')) {
            $name = config('app.top_news.name');
            $contents = DB::table('contents')
                -> select('contents.id', 'contents.title', 'contents.created_at')
                -> whereNull('contents.deleted_at')
                -> where(function ($query) {
                    $query -> where('is_top', 1);
                    $query -> orWhere('is_cus', 1);
                })
                -> orderBy('contents.weight', 'ASC')
                -> orderBy('contents.created_at', 'DESC')
                -> limit(10)
                -> paginate(10);
        } else {
            $module = DB::table('modules')
                -> select('name')
                -> whereNull('deleted_at')
                -> where('code', $module_code)
                -> first();
            if (is_null($module)) {
                abort(404);
            }
            $name = $module -> name;
            $contents = DB::table('contents_modules')
                -> select('contents.id', 'contents.title', 'contents.created_at')
                -> leftJoin('contents', 'contents.id', '=', 'contents_modules.c_id')
                -> leftJoin('modules', 'modules.id', '=', 'contents_modules.m_id')
                -> where('modules.code', $module_code)
                -> whereNull('modules.deleted_at')
                -> whereNull('contents.deleted_at')
                -> whereNull('contents_modules.deleted_at')
                -> orderBy('contents.weight', 'ASC')
                -> orderBy('contents.created_at', 'DESC')
                -> paginate(10);
        }
        return view('frontend.default.modules.list',
            ['contents' => $contents, 'code' => $module_code, 'module' => $name]);
    }

    public function show($module_code, $cid)
    {
        if ($module_code == config('app.top_news.code')) {
            $name = config('app.top_news.name');
        } else {
            $module = DB::table('modules')
                -> select('name')
                -> whereNull('deleted_at')
                -> where('code', $module_code)
                -> first();
            if (is_null($module)) {
                abort(404);
            }
            $name = $module -> name;
        }
        $content = DB::table('contents')
            -> select('title', 'source', 'content', 'created_at')
            -> whereNull('deleted_at')
            -> where('id', $cid)
            -> first();
        if (is_null($content)) {
            abort(404);
        }
        return view('frontend.default.modules.show',
            ['content' => $content, 'module' => $name, 'code' => $module_code]);
    }
}
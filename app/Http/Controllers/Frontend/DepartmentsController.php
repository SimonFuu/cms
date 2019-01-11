<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 2018/11/21
 * Time: 9:02 PM
 * https://www.fushupeng.com
 * contact@fushupeng.com
 */

namespace App\Http\Controllers\Frontend;


use Illuminate\Support\Facades\DB;

class DepartmentsController extends FrontendController
{
    public function list($department = null)
    {
        if (is_null($department)) {
            return abort(404);
        }
        $dep = DB::table('departments')
            -> select('id', 'code', 'name')
            -> where('code', $department)
            -> whereNull('deleted_at')
            -> first();
        if (is_null($dep)) {
            return abort(404);
        }
        $list = DB::table('contents')
            -> select('id', 'title', 'created_at', 'is_new')
            -> whereNull('deleted_at')
            -> where('dep_id', $dep -> id)
            -> orderBy('weight', 'ASC')
            -> orderBy('created_at', 'DESC')
            -> paginate(self::PER_PAGE_RECORD_COUNT);
        return view('frontend.default.departments.list', ['department' => $dep, 'contents' => $list]);
    }

    public function show($department = '', $cid = 0)
    {
        $dep = DB::table('departments')
            -> select('name', 'id')
            -> whereNull('deleted_at')
            -> where('code', $department)
            -> first();
        if (is_null($dep)) {
            abort(404);
        }
        $name = $dep -> name;
        $content = DB::table('contents')
            -> select('title', 'source', 'content', 'created_at')
            -> whereNull('deleted_at')
            -> where('id', $cid)
            -> where('dep_id', $dep -> id)
            -> first();
        if (is_null($content)) {
            abort(404);
        }
        return view('frontend.default.departments.show',
            ['content' => $content, 'module' => $name, 'code' => $department]);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 2018/11/9
 * Time: 3:39 PM
 * https://www.fushupeng.com
 * contact@fushupeng.com
 */

namespace App\Http\Controllers\Frontend;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexController extends FrontendController
{
    public function index()
    {
        $db_sections = DB::table('sections')
            -> select('modules.name', 'modules.code', 'sections.position', 'modules.id')
            -> leftJoin('modules', 'modules.id', '=', 'sections.m_id')
            -> whereNull('sections.deleted_at')
            -> whereNull('modules.deleted_at')
            -> orderBy('sections.weight', 'ASC')
            -> get();
        $mIds = $db_sections -> pluck('id') -> toArray();
        $contents = [];
        foreach ($mIds as $mid) {
            $contents[$mid] = DB::table('contents')
                -> select(
                    'contents.id',
                    'contents.title',
                    'contents.created_at',
                    'departments.name as dep'
                )
                -> leftJoin('contents_modules', 'contents_modules.c_id', '=', 'contents.id')
                -> leftJoin('users', 'users.id', '=', 'contents.publish_by')
                -> leftJoin('departments', 'departments.id', '=', 'users.dep_id')
                -> where('contents_modules.m_id', $mid)
                -> whereNull('contents_modules.deleted_at')
                -> whereNull('contents.deleted_at')
                -> orderBy('contents.weight', 'ASC')
                -> orderBy('contents.created_at', 'DESC')
                -> limit(10)
                -> get();
        }
        $sections = $db_sections -> groupBy('position');
        $topCarouselNews = DB::table('contents')
            -> select('id', 'title', 'abst', 'thumb')
            -> whereNull('deleted_at')
            -> where('is_cus', 1)
            -> orderBy('weight', 'ASC')
            -> orderBy('created_at', 'DESC')
            -> limit(5)
            -> get();

        if ($topCarouselNews -> isEmpty()) {
            $topCarouselNews = DB::table('contents')
                -> select('id', 'title', 'abst', 'thumb')
                -> whereNull('deleted_at')
                -> where('is_top', 0)
                -> orderBy('weight', 'ASC')
                -> orderBy('created_at', 'DESC')
                -> limit(5)
                -> get();
        }
        $topCommonNews = DB::table('contents') -> select('id', 'title', 'abst', 'thumb', 'created_at')
            -> whereNull('deleted_at')
            -> where('is_top', 1)
            -> orderBy('weight', 'ASC')
            -> orderBy('created_at', 'DESC')
            -> limit(7)
            -> get();
        if ($topCommonNews -> isEmpty()) {
            $topCommonNews = DB::table('contents') -> select('id', 'title', 'abst', 'thumb', 'created_at')
                -> whereNull('deleted_at')
                -> where('is_cus', 0)
                -> orderBy('weight', 'ASC')
                -> orderBy('created_at', 'DESC')
                -> limit(7)
                -> get();
        }
        return view('frontend.default.index', [
            'sections' => $sections,
            'contents' => $contents,
            'topCarouselNews' => $topCarouselNews,
            'topCommonNews' => $topCommonNews
        ]);
    }

    public function search(Request $request)
    {
        if ($request -> has('word')) {
            $contents = DB::table('contents')
                -> select('contents.id', 'contents.title', 'contents.created_at', 'modules.code', 'modules.name')
                -> leftJoin('contents_modules', 'contents_modules.c_id', '=', 'contents.id')
                -> leftJoin('modules', 'modules.id', '=', 'contents_modules.m_id')
                -> where('title', 'like', '%' . $request -> word . '%')
                -> whereNull('contents_modules.deleted_at')
                -> whereNull('contents.deleted_at')
                -> whereNull('modules.deleted_at')
                -> groupBy('contents.id')
                -> orderBy('contents.weight', 'ASC')
                -> orderBy('contents.created_at', 'DESC')
                -> paginate(10);
            return view('frontend.default.search', [
                'module' => '搜索',
                'contents' => $contents,
                'condition' => ['word' => is_null($request -> word) ? '' : $request -> word]
            ]);
        } else {
            return redirect(route('module.list', ['module' => config('app.top_news.code')]));
        }
    }
}
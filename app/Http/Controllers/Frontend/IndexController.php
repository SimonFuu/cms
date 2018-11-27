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
            -> where('modules.id', '<>', self::TOP_MODULE_ID)
            -> orderBy('sections.weight', 'ASC')
            -> get();
        $topModule = DB::table('modules')
            -> select('code')
            -> whereNull('deleted_at')
            -> where('id', self::TOP_MODULE_ID)
            -> first();
        $mIds = $db_sections -> pluck('id') -> toArray();
        $contents = [];
        foreach ($mIds as $mid) {
            $contents[$mid] = DB::table('contents')
                -> select(
                    'contents.id',
                    'contents.title',
                    'contents.created_at',
                    'departments.name as dep',
                    'departments.code'
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
        $topNews = DB::table('contents')
            -> select('id', 'title', 'abst', 'thumb', 'created_at')
            -> whereNull('deleted_at')
            -> where('sec_id', self::TOP_MODULE_ID)
            -> orderBy('weight', 'ASC')
            -> orderBy('created_at', 'DESC')
            -> limit(10)
            -> get();
        if ($topNews -> isEmpty()) {
            $topNews = DB::table('contents')
                -> select('id', 'title', 'abst', 'thumb', 'created_at')
                -> whereNull('deleted_at')
                -> whereNotNull('sec_id')
                -> orderBy('weight', 'ASC')
                -> orderBy('created_at', 'DESC')
                -> limit(10)
                -> get();
            if ($topNews -> isEmpty()) {
                $topNews = DB::table('contents')
                    -> select('id', 'title', 'abst', 'thumb', 'created_at')
                    -> whereNull('deleted_at')
                    -> orderBy('weight', 'ASC')
                    -> orderBy('created_at', 'DESC')
                    -> limit(10)
                    -> get();
            }
        }
        $navigation = $this -> navigationGenerator();
        $special = $this -> getSpecialLayouts();
        return view('frontend.default.index', [
            'sections' => $sections,
            'contents' => $contents,
            'topNews' => $topNews,
            'topModule' => $topModule,
            'navigation' => $navigation,
            'special' => $special
        ]);
    }


    private function getSpecialLayouts()
    {
        $html = '';
        $specials = DB::table('modules_special')
            -> select(
                'modules_special.weight',
                'modules_special.width',
                'modules_special.height',
                'modules_special.id',
                'modules.name',
                'modules.code',
                'modules.thumbnail'
            )
            -> leftJoin('modules', 'modules.id', '=', 'modules_special.mid')
            -> whereNull('modules.deleted_at')
            -> whereNull('modules_special.deleted_at')
            -> where('modules_special.mid', '>', 0)
            -> orderBy('modules_special.weight', 'ASC')
            -> get();
        foreach($specials as $special) {
            $html .= '<div id="special-image-container-' . $special -> id .'" class="col-xs-' . $special -> width .' special-layout-img-container">';
            $html .= '<div class="special-item"><a href="' .route('special.list', ['code' => $special -> code]). '">';
            $html .= '<img src="'. $special -> thumbnail .'" width="100%" height="';
            $html .= ((is_null($special -> height) || $special -> height == 0) ? 'auto' : $special -> height) . '" alt=""></a></div></div>';
        }
        return $html;
    }
    private function navigationGenerator()
    {
        $db_navigation = DB::table('links')
            -> select('id', 'name', 'link', 'parent_id')
            -> whereNull('deleted_at')
            -> orderBy('weight', 'ASC')
            -> get();
        if ($db_navigation -> isEmpty()) {
            return false;
        }
        $db_navigation = $db_navigation -> groupBy('parent_id');
        if (!isset($db_navigation[0])) {
            return false;
        }
        $groups = $db_navigation[0];
        $departments = DB::table('departments')
            -> select('name', 'code as link')
            -> whereNull('deleted_at')
            -> where('parent_id', '>', 0)
            -> where('id', '<>', self::LEADERS_DEP_ID)
            -> orderBy('weight', 'ASC')
            -> get();
        foreach ($groups as $group) {
            if ($group -> id == 1) {
                $group -> links = $departments;
            } else {
                $group -> links = isset($db_navigation[$group -> id]) ? $db_navigation[$group -> id] : [];
            }
        }
        return $groups;
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
                -> paginate(self::PER_PAGE_RECORD_COUNT);
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
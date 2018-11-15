<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 2018/11/9
 * Time: 3:39 PM
 * https://www.fushupeng.com
 * contact@fushupeng.com
 */

namespace App\Http\Controllers\Backend;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContentsController extends BackendController
{
    public function list(Request $request)
    {
        $db_modules = DB::table('modules')
            -> select('id', 'name')
            -> whereNull('deleted_at')
            -> orderBy('weight', 'ASC')
            -> get();
        if ($db_modules -> isEmpty()) {
            return redirect(route('backend.index')) -> with('error', '当前没有可用模块');
        }
        $modules = [];
        foreach ($db_modules as $module) {
            $modules[$module -> id] = $module -> name;
        }
        $contents = DB::table('contents')
            -> select('contents.id', 'contents.title', 'contents.source', 'contents.weight', 'users.name', 'contents.created_at')
            -> leftJoin('users', 'users.id', '=', 'contents.publish_by')
            -> whereNull('contents.deleted_at')
            -> where(function ($query) use ($request) {
                if ($request -> has('title') && !is_null($request -> title)) {
                    $query -> where('contents.title', 'like', '%' . $request -> title . '%');
                    $this -> searchConditions['title'] = $request -> title;
                }
                if ($request -> has('m_id') && !is_null($request -> m_id)) {
                    $query -> where('contents.m_ids', 'like', '%[' . $request -> m_id . ']%');
//                    $query -> orWhere('sec_id', $request -> m_id);
                    $this -> searchConditions['m_id'] = $request -> m_id;
                }
            })
            -> orderBy('contents.weight', 'ASC')
            -> orderBy('contents.created_at', 'DESC')
            -> paginate(20);
        return view('backend.contents.list', ['modules' => $modules, 'contents' => $contents, 'condition' => $this -> searchConditions]);
    }

    public function form(Request $request)
    {
        $db_sections = DB::table('sections')
            -> select('modules.name', 'modules.id')
            -> leftJoin('modules', 'modules.id', '=', 'sections.m_id')
            -> whereNull('sections.deleted_at')
            -> whereNull('modules.deleted_at')
            -> orderBy('sections.weight', 'ASC')
            -> get();
        $db_navigation = DB::table('navigation')
            -> select('modules.name', 'modules.id')
            -> leftJoin('modules', 'modules.id', '=', 'navigation.m_id')
            -> whereNull('navigation.deleted_at')
            -> whereNull('modules.deleted_at')
            -> orderBy('navigation.weight', 'ASC')
            -> get();

        if ($db_sections -> isEmpty() || $db_navigation -> isEmpty()) {
            return redirect(route('backend.index')) -> with('error', '当前没有可用模块');
        }
        $sections = $navigation = [];
        $content = null;
        foreach ($db_sections as $section) {
            $sections[$section -> id] = $section -> name;
        }
        foreach ($db_navigation as $value) {
            $navigation[$value -> id] = $value -> name;
        }
        if ($request -> has('id')) {
            $content = DB::table('contents')
                -> select('id', 'title', 'is_top', 'is_cus', 'weight', 'content', 'thumb', 'abst', 'sec_id', 'source')
                -> whereNull('deleted_at')
                -> where('id', $request -> id)
                -> first();
            $module_ids = DB::table('contents_modules')
                -> select('m_id')
                -> whereNull('deleted_at')
                -> where('c_id', $request -> id)
                -> get();
            if (is_null($content) || $module_ids -> isEmpty()) {
                return redirect(route('backend.contents')) -> with('error', '数据异常，请联系管理员');
            }
            $nav_ids = $module_ids -> pluck('m_id') -> toArray();
            $content -> nav_ids = $nav_ids;
        }
        return view('backend.contents.form',
            ['content' => $content, 'navigation' => $navigation, 'sections' => $sections]);
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|max:255|min:1',
            'navigation' => 'required|array',
            'section' => 'exists:sections,m_id,deleted_at,NULL',
            'is_cus' => 'required|boolean',
            'is_top' => 'required|boolean',
            'source' => 'required|max:255',
            'weight' => 'required|numeric|max:1000|min:0',
            'thumbnail' => 'required|max:255|min:5',
            'cont' => 'required|min:10',
            'abst' => 'max:255',
        ];
        $messages = [
            'title.required' => '请输入标题',
            'title.max' => '标题长度不要超过:max',
            'title.min' => '标题长度不要少于:min',
            'navigation.required' => '请选择推送板块',
            'navigation.array' => '推送板块不存在',
            'section.exists' => '您选择的首页板块不存在',
            'is_cus.required' => '请选择是否推送首页轮播头条',
            'is_cus.boolean' => '是否推送首页轮播头条格式错误',
            'is_top.required' => '请选择是否推送首页普通头条',
            'is_top.boolean' => '是否推送首页普通头条格式错误',
            'weight.required' => '请输入文章权重',
            'weight.numeric' => '文章展示权重格式错误',
            'weight.max' => '文章展示权重不能大于:max',
            'weight.min' => '文章展示权重不能小于:min',
            'thumbnail.required' => '请上传文章缩略图',
            'thumbnail.max' => '文章缩略图异常，请联系管理员【max】',
            'thumbnail.min' => '文章缩略图异常，请联系管理员【min】',
            'cont.required' => '请输入文章内容',
            'cont.min' => '文章内容不要少于:min',
            'abst.max' => '文章摘要不要超过:max',
            'source.max' => '来源长度不要超过:max',
            'source.required' => '请输入文章来源'
        ];

        $this -> validate($request, $rules, $messages);

        $data = [
            'title' => $request -> title,
            'source' => $request -> source,
            'is_top' => $request -> is_top,
            'is_cus' => $request -> is_cus,
            'thumb' => $request -> thumbnail,
            'weight' => $request -> weight,
            'abst' => $request -> abst,
            'content' => $request -> cont,
        ];
        if (!is_null($request -> section)) {
            $data['sec_id'] = $request -> section;
        }

        $nav_ids = DB::table('navigation')
            -> select('m_id')
            -> whereNull('deleted_at')
            -> whereIn('m_id', $request -> navigation)
            -> get();
        if ($nav_ids -> isEmpty()) {
            return redirect() -> back() -> with('error', '您选择的推送板块不存在或已被删除');
        }
        $nav_ids -> push(['m_id' => (int)$request -> section]);
        $nav_ids = $nav_ids -> pluck('m_id') -> toArray();

        if ($request -> has('id')) {
            $exists = DB::table('contents')
                -> where('id', $request -> id) -> whereNull('deleted_at') -> exists();
            if (!$exists) {
                return redirect(route('backend.contents')) -> with('error', '该文章不存在或已被删除!');
            }
            $module_ids = [];
            $m_ids = '';
            foreach ($nav_ids as $nav_id) {
                $m_ids .= '[' . $nav_id . ']';
                $module_ids[] = [
                    'm_id' => $nav_id,
                    'c_id' => $request -> id
                ];
            }
            DB::beginTransaction();
            try {
                $data['m_ids'] = $m_ids;
                DB::table('contents') -> where('id', $request -> id)
                    -> update($data);
                DB::table('contents_modules')
                    -> where('id', $request -> id)
                    -> whereNull('deleted_at')
                    -> update(['deleted_at' => date('Y-m-d H:i:s')]);
                DB::table('contents_modules') -> insert($module_ids);
                DB::commit();
                return redirect(route('backend.contents')) -> with('success', '保存成功');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect(route('backend.contents')) -> with('error', '保存失败，请联系管理员。' . $e -> getMessage());
            }
        } else {
            DB::beginTransaction();
            try {
                $data['publish_by'] = 1;
                $cid = DB::table('contents') -> insertGetId($data);
                $module_ids = [];
                $m_ids = '';
                foreach ($nav_ids as $nav_id) {
                    $m_ids .= '[' . $nav_id . ']';
                    $module_ids[] = [
                        'm_id' => $nav_id,
                        'c_id' => $cid
                    ];
                }

                DB::table('contents') -> update(['m_ids' => $m_ids]);
                DB::table('contents_modules') -> insert($module_ids);
                DB::commit();
                return redirect(route('backend.contents')) -> with('success', '保存成功');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect(route('backend.contents')) -> with('error', '保存失败，请联系管理员。' . $e -> getMessage());
            }
        }
    }

    public function delete(Request $request)
    {
        if ($request -> has('id')) {
            DB::beginTransaction();
            try {
                DB::table('contents_modules')
                    -> where('c_id', $request -> id)
                    -> update(['deleted_at' => date('Y-m-d H:i:s')]);
                DB::table('contents')
                    -> where('id', $request -> id)
                    -> update(['deleted_at' => date('Y-m-d H:i:s')]);
                DB::commit();
                return redirect(route('backend.contents')) -> with('success', '删除成功');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect(route('backend.contents')) -> with('error', '删除失败，请联系管理员。' . $e -> getMessage());
            }
        } else {
            return redirect(route('backend.contents')) -> with('error', '删除失败，请提供文章ID');
        }
    }
}
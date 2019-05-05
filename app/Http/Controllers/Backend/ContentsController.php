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
use Auth;
use Illuminate\Support\Facades\DB;


class ContentsController extends BackendController
{
    private $orderParam = [];

    public function list(Request $request)
    {
        $db_modules = DB::table('modules')
            -> select('modules.id', 'modules.name')
            -> leftJoin('departments_modules', 'departments_modules.mid', '=', 'modules.id')
            -> where(function ($query) {
                $isAdmin = DB::table('roles_users')
                    -> whereNull('deleted_at')
                    -> where('uid', Auth::id())
                    -> where('rid', 1)
                    -> exists();
                if (!$isAdmin) {
                    $query -> where('departments_modules.dep_id', Auth::user() -> dep_id);
                }
                $query -> whereNull('modules.deleted_at');
                $query -> whereNull('departments_modules.deleted_at');
                $query -> where('modules.type', '<>', self::LINKS_MODULE_TYPE);
            })
            -> orderBy('modules.weight', 'ASC')
            -> get();
        if ($db_modules -> isEmpty()) {
            return redirect(route('backend.index')) -> with('error', '您当前没有可以操作的板块');
        }
        $modules = [];
        foreach ($db_modules as $module) {
            $modules[$module -> id] = $module -> name;
        }
        $orderArr = [];
        if ($request -> has('order')) {
            if (isset($request -> order['ctime'])) {
                if ($request -> order['ctime'] == 'asc') {
                    $this -> searchConditions['order']['ctime'] = 'asc';
                    $this -> orderParam['order']['ctime'] = 'desc';
                    $orderArr[] = 'itls_contents.created_at asc';
                } elseif ($request -> order['ctime'] == 'desc') {
                    $this -> searchConditions['order']['ctime'] = 'desc';
                    $this -> orderParam['order']['ctime'] = 'asc';
                    $orderArr[] = 'itls_contents.created_at desc';
                } else {
                    $orderArr[] = 'itls_contents.created_at desc';
                }
            } else if (isset($request -> order['weight'])) {
                if ($request -> order['weight'] == 'asc') {
                    $this -> searchConditions['order']['weight'] = 'asc';
                    $this -> orderParam['order']['weight'] = 'desc';
                    $orderArr[] = 'itls_contents.weight asc';
                } elseif ($request -> order['weight'] == 'desc') {
                    $this -> searchConditions['order']['weight'] = 'desc';
                    $this -> orderParam['order']['weight'] = 'asc';
                    $orderArr[] = 'itls_contents.weight desc';
                }
                $orderArr[] = 'itls_contents.created_at desc';
            } else if (isset($request -> order['dep'])) {
                if ($request -> order['dep'] == 'asc') {
                    $this -> searchConditions['order']['dep'] = 'asc';
                    $this -> orderParam['order']['dep'] = 'desc';
                    $orderArr[] = 'convert(itls_departments.name using gbk) asc';
                } elseif ($request -> order['dep'] == 'desc') {
                    $this -> searchConditions['order']['dep'] = 'desc';
                    $this -> orderParam['order']['dep'] = 'asc';
                    $orderArr[] = 'convert(itls_departments.name using gbk)  desc';
                }
                $orderArr[] = 'itls_contents.created_at desc';

            } else if (isset($request -> order['pub'])) {
                if ($request -> order['pub'] == 'asc') {
                    $this -> searchConditions['order']['pub'] = 'asc';
                    $this -> orderParam['order']['pub'] = 'desc';
                    $orderArr[] = 'convert(itls_users.name using gbk) desc';
                } elseif ($request -> order['pub'] == 'desc') {
                    $this -> searchConditions['order']['pub'] = 'desc';
                    $this -> orderParam['order']['pub'] = 'asc';
                    $orderArr[] = 'convert(itls_users.name using gbk) desc';
                }
                $orderArr[] = 'itls_contents.created_at desc';
            } else if (isset($request -> order['source'])) {
                if ($request -> order['source'] == 'asc') {
                    $this -> searchConditions['order']['source'] = 'asc';
                    $this -> orderParam['order']['source'] = 'desc';
                    $orderArr[] = 'convert(itls_contents.source using gbk) asc';
                } elseif ($request -> order['source'] == 'desc') {
                    $this -> searchConditions['order']['source'] = 'desc';
                    $this -> orderParam['order']['source'] = 'asc';
                    $orderArr[] = 'convert(itls_contents.source using gbk) desc';
                }
                $orderArr[] = 'itls_contents.created_at desc';
            }

            else {
                $orderArr[] = 'itls_contents.created_at desc';
            }
        } else {
            $orderArr[] = 'itls_contents.created_at desc';
        }
        $orderStr = implode(', ', $orderArr);
        $contents = DB::table('contents')
            -> select('contents.id', 'contents.title', 'contents.source', 'contents.weight', 'users.name', 'contents.created_at', 'departments.name as dep_name')
            -> leftJoin('users', 'users.id', '=', 'contents.publish_by')
            -> leftJoin('departments', 'departments.id', '=', 'contents.dep_id')
            -> leftJoin('contents_modules', 'contents_modules.c_id', '=', 'contents.id')
            -> where(function ($query) {
                $isAdmin = DB::table('roles_users')
                    -> whereNull('deleted_at')
                    -> where('uid', Auth::id())
                    -> where('rid', 1)
                    -> exists();
                if (!$isAdmin) {
                    $query -> where('contents.publish_by', Auth::id());
                }
                $query -> whereNull('contents.deleted_at');
                $query -> whereNull('contents_modules.deleted_at');
            })
            -> where(function ($query) use ($request) {
                if ($request -> has('title') && !is_null($request -> title)) {
                    $query -> where('contents.title', 'like', '%' . $request -> title . '%');
                    $this -> searchConditions['title'] = $request -> title;
                    $this -> orderParam['title'] = $request -> title;
                }
                if ($request -> has('m_id') && !is_null($request -> m_id)) {
                    $query -> where('contents.m_ids', 'like', '%"mid:' . $request -> m_id . '"%');
                    $this -> searchConditions['m_id'] = $request -> m_id;
                    $this -> orderParam['m_id'] = $request -> m_id;
                }
            })
//            -> where(function ($query) use ($dep_ids) {
//                $query -> where('contents.dep_id', Auth::user() -> dep_id);
//                $query -> orWhereIn('contents_modules.m_id', $dep_ids);
//            })
//            -> orderBy('contents.weight', 'ASC')
            -> orderByRaw($orderStr)
//            -> orderBy('contents.created_at', 'DESC')
            -> groupBy('contents.id')
            -> paginate(self::PER_PAGE_RECORD_COUNT);
        return view('backend.contents.list', [
            'modules' => $modules,
            'contents' => $contents,
            'condition' => $this -> searchConditions,
            'orderParams' => $this -> orderParam,
        ]);
    }

    public function form(Request $request)
    {
        $content = null;
        $db_modules = DB::table('modules')
            -> select('modules.name', 'modules.id')
            -> leftJoin('departments_modules', 'departments_modules.mid', '=', 'modules.id')
            -> whereNull('departments_modules.deleted_at')
            -> whereNull('modules.deleted_at')
            -> whereIn('modules.type', [1,2])
            -> where('departments_modules.dep_id', Auth::user() -> dep_id)
            -> orderBy('modules.weight', 'ASC')
            -> get();
//        $db_sections = DB::table('sections')
//            -> select('modules.name', 'modules.id', 'modules.weight')
//            -> leftJoin('modules', 'modules.id', '=', 'sections.m_id')
//            -> leftJoin('departments_modules', 'departments_modules.mid', '=', 'modules.id')
//            -> whereNull('sections.deleted_at')
//            -> whereNull('modules.deleted_at')
//            -> whereNull('departments_modules.deleted_at')
//            -> where('departments_modules.dep_id', Auth::user() -> dep_id)
//            -> orderBy('sections.weight', 'ASC')
//            -> get();
//
//        $db_navigation = DB::table('navigation')
//            -> select('modules.name', 'modules.id', 'modules.weight')
//            -> leftJoin('modules', 'modules.id', '=', 'navigation.m_id')
//            -> leftJoin('departments_modules', 'departments_modules.mid', '=', 'modules.id')
//            -> whereNull('navigation.deleted_at')
//            -> whereNull('modules.deleted_at')
//            -> where('modules.type', '<>', self::LINKS_MODULE_TYPE)
//            -> whereNull('departments_modules.deleted_at')
//            -> where('departments_modules.dep_id', Auth::user() -> dep_id)
//            -> orderBy('navigation.weight', 'ASC')
//            -> get();
//        $db_specials = DB::table('modules')
//            -> select('modules.id', 'modules.name', 'modules.weight')
//            -> leftJoin('departments_modules', 'departments_modules.mid', '=', 'modules.id')
//            -> leftJoin('modules_special', 'modules_special.mid', '=', 'modules.id')
//            -> where('modules.type', self::SPECIAL_MODULE_TYPE)
//            -> whereNull('modules_special.deleted_at')
//            -> whereNull('departments_modules.deleted_at')
//            -> whereNull('modules.deleted_at')
//            -> where('departments_modules.dep_id', Auth::user() -> dep_id)
//            -> get();
//        if ($db_specials -> isNotEmpty()) {
//            foreach ($db_specials as $special) {
//                $db_navigation -> push($special);
//            }
//        }
//        $db_navigation = $db_navigation -> sortBy('weight');
//        $sections = $navigation = [];
//        $content = null;
//        if ($db_sections -> isNotEmpty()) {
//            foreach ($db_sections as $section) {
//                $sections[$section -> id] = $section -> name;
//            }
//        }
//        if ($db_navigation -> isNotEmpty()) {
//            foreach ($db_navigation as $value) {
//                $navigation[$value -> id] = $value -> name;
//            }
//        }
        $modules = [];
        if ($db_modules -> isNotEmpty()) {
            foreach ($db_modules as $value) {
                $modules[$value -> id] = $value -> name;
            }
        }
        if ($request -> has('id')) {
            $content = DB::table('contents')
                -> select('id', 'title', 'is_top', 'is_cus', 'weight', 'content', 'thumb', 'abst', 'sec_id', 'source')
                -> where(function ($query) {
                    $isAdmin = DB::table('roles_users')
                        -> whereNull('deleted_at')
                        -> where('uid', Auth::id())
                        -> where('rid', 1)
                        -> exists();
                    if (!$isAdmin) {
                        $query -> where('publish_by', Auth::id());
                    }
                    $query -> whereNull('deleted_at');
                })
                -> where('id', $request -> id)
                -> first();
            if (is_null($content)) {
                return redirect(route('backend.contents')) -> with('error', '文章已删除或您没有权限修改该文章');
            }
            $module_ids = DB::table('contents_modules')
                -> select('m_id')
                -> whereNull('deleted_at')
                -> where('c_id', $request -> id)
                -> get();
            $nav_ids = $module_ids -> pluck('m_id') -> toArray();
            $content -> nav_ids = $nav_ids;
        }
//        return view('backend.contents.form',
//            ['content' => $content, 'navigation' => $navigation, 'sections' => $sections]);
        return view('backend.contents.form',
            ['content' => $content, 'modules' => $modules]);
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|max:255|min:1',
//            'navigation' => 'required|array',
//            'navigation' => 'required_without:section|array',
//            'section' => 'required_without:navigation|nullable|exists:sections,m_id,deleted_at,NULL',
//            'is_cus' => 'required|boolean',
//            'is_top' => 'required|boolean',
            'module' => 'required|array',
            'source' => 'required|max:255',
            'weight' => 'required|numeric|max:1000|min:0',
            'thumbnail' => 'required_if:section,' . self::TOP_MODULE_ID. '|max:255',
            'cont' => 'required|min:10',
            'abst' => 'max:255',
        ];
        $messages = [
            'title.required' => '请输入标题',
            'title.max' => '标题长度不要超过:max',
            'title.min' => '标题长度不要少于:min',
            'navigation.required_without' => '请选择推送的导航栏目',
            'navigation.array' => '选择的导航栏目不存在',
            'module.required' => '请选择要推送的板块',
            'module.array' => '板块格式不正确',
            'section.required_without' => '请选择要推送的首页板块',
            'section.exists' => '您选择的首页板块不存在',
            'is_cus.required' => '请选择是否推送首页轮播头条',
            'is_cus.boolean' => '是否推送首页轮播头条格式错误',
            'is_top.required' => '请选择是否推送首页普通头条',
            'is_top.boolean' => '是否推送首页普通头条格式错误',
            'weight.required' => '请输入文章权重',
            'weight.numeric' => '文章展示权重格式错误',
            'weight.max' => '文章展示权重不能大于:max',
            'weight.min' => '文章展示权重不能小于:min',
            'thumbnail.required_if' => '请上传文章缩略图',
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
        $mids= DB::table('modules')
            -> select('id')
            -> whereNull('deleted_at')
            -> whereIn('id', $request -> module)
            -> get();
        if ($mids -> isEmpty()) {
            return redirect() -> back() -> with('error', '您选择的板块已删除');
        }
        $mIds = $mids -> pluck('id') -> toArray();

//        if ($request -> has('section') && !is_null($request -> section)) {
//            $data['sec_id'] = $request -> section;
//            $mIds[] = $request -> section;
//        }
//
//        if ($request -> has('navigation') && !is_null($request -> navigation)) {
//            $nav_ids = DB::table('navigation')
//                -> select('m_id')
//                -> whereNull('deleted_at')
//                -> whereIn('m_id', $request -> navigation)
//                -> get();
//            if ($nav_ids -> isEmpty()) {
//                return redirect() -> back() -> with('error', '您选择的推送板块不存在或已被删除');
//            }
//            $nav_ids -> push(['m_id' => (int)$request -> section]);
//            $nav_ids = $nav_ids -> pluck('m_id') -> toArray();
//            $mIds = array_merge($mIds, $nav_ids);
//        }

        if ($request -> has('id')) {
            $content = DB::table('contents')
                -> select('created_at')
                -> where(function ($query) use ($request) {
                    $isAdmin = DB::table('roles_users')
                        -> whereNull('deleted_at')
                        -> where('uid', Auth::id())
                        -> where('rid', 1)
                        -> exists();
                    if (!$isAdmin) {
                        $query -> where('publish_by', Auth::id());
                    }
                    $query -> where('id', $request -> id);
                })
                -> whereNull('deleted_at')
                -> first();
            if (is_null($content)) {
                return redirect(route('backend.contents')) -> with('error', '该文章不存在或您没有编辑权限!');
            }
//            $hasNew = DB::table('contents')
//                -> whereNull('deleted_at')
//                -> where('created_at', '>=', date('Y-m-d 23:59:59', strtotime($content -> created_at)))
//                -> exists();
            DB::beginTransaction();
            try {
                if ($mIds) {
                    $module_ids = [];
                    $m_ids = [];
                    foreach ($mIds as $mid) {
                        $m_ids[] = 'mid:' . $mid;
                        $module_ids[] = [
                            'm_id' => $mid,
                            'c_id' => $request -> id,
//                            'is_new' => $hasNew ? 0 : 1,
                        ];
                    }
                    $data['m_ids'] = json_encode($m_ids);
                    DB::table('contents_modules')
                        -> where('c_id', $request -> id)
                        -> whereNull('deleted_at')
                        -> update(['deleted_at' => date('Y-m-d H:i:s')]);
                    DB::table('contents_modules') -> insert($module_ids);
                }
                DB::table('contents') -> where('id', $request -> id)
                    -> update($data);
                DB::commit();
                return redirect(route('backend.contents')) -> with('success', '保存成功');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect(route('backend.contents')) -> with('error', '保存失败，请联系管理员。' . $e -> getMessage());
            }
        } else {
            DB::beginTransaction();
            try {
                $data['publish_by'] = Auth::id();
                $data['dep_id'] = Auth::user() -> dep_id;
                $cid = DB::table('contents') -> insertGetId($data);
                if ($mIds) {
                    $module_ids = [];
                    $m_ids = [];
                    foreach ($mIds as $mid) {
                        $m_ids[] = 'mid:' . $mid;
                        $module_ids[] = [
                            'm_id' => $mid,
                            'c_id' => $cid
                        ];
                    }
                    DB::table('contents') -> where('id', $cid) -> update(['m_ids' => json_encode($m_ids)]);
                    DB::table('contents_modules') -> insert($module_ids);
                    DB::table('contents_modules')
                        -> whereIn('m_id', $mIds)
                        -> whereNull('deleted_at')
                        -> where('created_at', '<', date('Y-m-d 00:00:00'))
                        -> update(['is_new' => 0]);
                }
                DB::table('contents')
                    -> where('dep_id', Auth::user() -> dep_id)
                    -> where('created_at', '<', date('Y-m-d 00:00:00'))
                    -> update(['is_new' => 0]);
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
        # TODO 检查是否有权限进行删除
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
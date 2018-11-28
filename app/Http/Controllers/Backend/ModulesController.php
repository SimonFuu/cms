<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 2018/11/9
 * Time: 4:59 PM
 * https://www.fushupeng.com
 * contact@fushupeng.com
 */

namespace App\Http\Controllers\Backend;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModulesController extends BackendController
{
    /**
     * 板块首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function list()
    {
        $modules = DB::table('modules')
            -> select(
                'modules.id',
                'modules.name',
                'modules.weight',
                'module_types.name as type',
                'modules.created_at'
            )
            -> leftJoin('module_types', 'module_types.id', '=', 'modules.type')
            -> whereNull('modules.deleted_at')
            -> orderBy('modules.weight', 'ASC')
            -> get();
        return view('backend.modules.modules', ['modules' => $modules]);
    }

    /**
     * 编辑 / 添加模块
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function form(Request $request)
    {
        $dbDepartments = DB::table('departments')
            -> select('id', 'name', 'parent_id')
            -> whereNull('deleted_at')
            -> orderBy('weight', 'ASC')
            -> get();
        if ($dbDepartments -> isEmpty()) {
            return redirect(route('backend.modules')) -> with('error', '请先创建部门');
        }
        $departments = $this -> treeView($dbDepartments, 'parent_id');

        $db_types = DB::table('module_types')
            -> select('id', 'name')
            -> get();
        $types = [];
        foreach ($db_types as $t) {
            $types[$t -> id] = $t -> name;
        }
        if ($request -> has('id')) {
            $module = DB::table('modules')
                -> select('*')
                -> where('id', $request -> id)
                -> whereNull('deleted_at')
                -> first();
            if (is_null($module)) {
                return redirect(route('backend.modules')) -> with('error', '未查询到该板块，请重试！');
            } else {
                $auth_deps = DB::table('departments')
                    -> select('departments.id', 'departments.name')
                    -> leftJoin('departments_modules', 'departments_modules.dep_id', '=', 'departments.id')
                    -> whereNull('departments_modules.deleted_at')
                    -> whereNull('departments.deleted_at')
                    -> where('departments_modules.mid', $request -> id)
                    -> orderBy('departments.weight', 'ASC')
                    -> get();
                if ($auth_deps -> isNotEmpty()) {
                    $module -> auth_deps = [
                        'names' => $auth_deps -> pluck('name') -> implode(','),
                        'ids' => $auth_deps -> pluck('id') -> toArray()
                    ];

                } else {
                    $module -> auth_deps = [
                        'names' => '',
                        'ids' => []
                    ];
                }
                return view('backend.modules.form', ['module' => $module,'types' => $types, 'departments' => $departments]);
            }
        } else {
            return view('backend.modules.form', ['types' => $types, 'departments' => $departments]);
        }
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:15|min:2|unique:modules,name,'
                . ($request -> has('id') ? $request -> id : 'NULL') . ',id,deleted_at,NULL',
            'code' => 'required|max:255|min:2|unique:modules,code,'
                . ($request -> has('id') ? $request -> id : 'NULL') . ',id,deleted_at,NULL',
            'desc' => 'required|max:255|min:2',
            'weight' => 'required|numeric|max:1000|min:0',
            'type' => 'required|exists:module_types,id,deleted_at,NULL',
            'thumbnail' => 'required_if:type,2|max:255',
            'departments' => 'required|array',
        ];
        $messages = [
            'name.required' => '请输入板块名称',
            'name.max' => '板块名称长度不要超过:max',
            'name.min' => '板块名称长度不要少于:min',
            'name.unique' => '该板块名称已存在',
            'code.required' => '请输入板块代码',
            'code.max' => '板块代码长度不要超过:max',
            'code.min' => '板块代码长度不要少于:min',
            'code.unique' => '板块代码已存在',
            'desc.required' => '请输入描述信息',
            'desc.max' => '板块描述不要超过:max',
            'desc.min' => '板块描述不要少于:min',
            'weight.required' => '请输入板块权重',
            'weight.max' => '板块权重不要大于:max',
            'weight.min' => '板块权重不要小于:min',
            'type.required' => '请选择板块类型',
            'type.exists' => '板块类型不存在',
            'thumbnail.required_if' => '请上传专题图',
            'thumbnail.max' => '专题图异常，请联系管理员【:max】',
            'departments.required' => '请选择授权部门',
            'departments.array' => '部门格式不正确'
        ];
        $this -> validate($request, $rules, $messages);

        $data = [
            'name' => $request -> name,
            'desc' => $request -> desc,
            'code' => $request -> code,
            'weight' => $request -> weight,
            'type' => $request -> type,
            'thumbnail' => $request -> has('thumbnail') ? $request -> thumbnail : null
        ];

        $departments = DB::table('departments')
            -> select('id')
            -> whereIn('id', $request -> departments)
            -> whereNull('deleted_at')
            -> get();
        if ($departments -> isEmpty()) {
            return redirect(route('backend.modules')) -> with('error', '所选的部门不存在');
        }
        $dep_ids = $departments -> pluck('id');

        DB::beginTransaction();
        try {
            if ($request -> has('id')) {
                DB::table('modules')
                    -> where('id', $request -> id)
                    -> update($data);
                DB::table('departments_modules') -> where('mid', $request -> id)
                    -> update(['deleted_at' => date('Y-m-d H:i:s')]);
                $mid = $request -> id;
            } else {
                $mid = DB::table('modules') -> insertGetId($data);
            }
            $ids = [];
            foreach ($dep_ids as $id) {
                $ids[] = [
                    'mid' => $mid,
                    'dep_id' => $id
                ];
            }
            DB::table('departments_modules') -> insert($ids);
            DB::commit();
            return redirect(route('backend.modules')) -> with('success', '保存成功！');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect(route('backend.modules'))
                -> with('error', sprintf('保存失败，请联系管理员。【%s】', $e -> getMessage()));
        }

    }
    /**
     * 删除模块
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        if ($request -> has('id')) {
            DB::table('modules')
                -> where('id', $request -> id) -> update(['deleted_at' => date('Y-m-d H:i:s')]);
            DB::table('departments_modules') -> where('mid', $request -> id)
                -> update(['deleted_at' => date('Y-m-d H:i:s')]);
            return redirect(route('backend.modules')) -> with('success', '删除成功！');
        } else {
            return redirect(route('backend.modules')) -> with('error', '删除失败，请提供所要删除的导航板块ID！');
        }
    }
}
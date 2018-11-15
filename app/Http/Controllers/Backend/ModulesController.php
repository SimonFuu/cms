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
            -> select('id', 'name', 'desc', 'weight', 'code', 'created_at')
            -> whereNull('deleted_at')
            -> orderBy('weight', 'ASC')
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
        if ($request -> has('id')) {
            $module = DB::table('modules')
                -> select('*')
                -> where('id', $request -> id)
                -> whereNull('deleted_at')
                -> first();
            if (is_null($module)) {
                return redirect(route('backend.modules')) -> with('error', '未查询到该板块，请重试！');
            } else {
                return view('backend.modules.form', ['module' => $module]);
            }
        } else {
            return view('backend.modules.form');
        }
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:10|min:2|unique:modules,name,'
                . ($request -> has('id') ? $request -> id : 'NULL') . ',id,deleted_at,NULL',
            'code' => 'required|max:10|min:2|unique:modules,code,'
                . ($request -> has('id') ? $request -> id : 'NULL') . ',id,deleted_at,NULL',
            'desc' => 'required|max:255|min:2',
            'weight' => 'required|numeric|max:1000|min:0'
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
        ];
        $this -> validate($request, $rules, $messages);

        $data = [
            'name' => $request -> name,
            'desc' => $request -> desc,
            'code' => $request -> code,
            'weight' => $request -> weight
        ];
        try {
            if ($request -> has('id')) {
                DB::table('modules')
                    -> where('id', $request -> id)
                    -> update($data);
            } else {
                DB::table('modules') -> insert($data);
            }
            return redirect(route('backend.modules')) -> with('success', '保存成功！');
        } catch (\Exception $e) {
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
            return redirect(route('backend.modules')) -> with('success', '删除成功！');
        } else {
            return redirect(route('backend.modules')) -> with('error', '删除失败，请提供所要删除的导航板块ID！');
        }
    }
}
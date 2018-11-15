<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 2018/11/9
 * Time: 10:27 PM
 * https://www.fushupeng.com
 * contact@fushupeng.com
 */

namespace App\Http\Controllers\Backend;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NavigationController extends BackendController
{
    /**
     * 导航列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function list(Request $request)
    {
        if ($request -> has('id') && $request -> ajax()) {
            $navigation = DB::table('navigation')
                -> select('navigation.id', 'navigation.weight', 'navigation.m_id', 'modules.name')
                -> leftJoin('modules', 'modules.id', '=', 'navigation.m_id')
                -> whereNull('navigation.deleted_at')
                -> whereNull('modules.deleted_at')
                -> where('navigation.id', $request -> id)
                -> first();
            if (is_null($navigation)) {
                return $this -> response([], 404);
            } else {
                 return $this -> response($navigation, 200);
            }

        } else {
            $navigation = DB::table('navigation')
                -> select('navigation.id', 'navigation.weight', 'navigation.m_id', 'modules.name', 'modules.code')
                -> leftJoin('modules', 'modules.id', '=', 'navigation.m_id')
                -> whereNull('navigation.deleted_at')
                -> whereNull('modules.deleted_at')
                -> orderBy('weight', 'ASC')
                -> get();
            $exists_modules_id = $navigation -> pluck('m_id') -> toArray();
            $db_modules = DB::table('modules')
                -> select('name', 'id')
                -> whereNull('deleted_at')
                -> whereNotIn('id', $exists_modules_id)
                -> get();
            $modules = [];
            if ($db_modules -> isNotEmpty()) {
                foreach ($db_modules as $module) {
                    $modules[$module -> id] = $module -> name;
                }
            }
            return view('backend.navigation.navigation', ['modules' => $modules, 'navigation' => $navigation]);
        }
    }

    /**
     * 保存Navigation
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        if ($request -> has('id') && $request -> id > 0) {
            $rules = [
                'm_id' => 'required|exists:modules,id,deleted_at,NULL|unique:navigation,m_id,'
                    . ($request -> has('id') ? $request -> id : 'NULL') . ',id,deleted_at,NULL',
                'weight' => 'required|max:1000|min:1'
            ];
        } else {
            $rules = [
                'modal_m_id' => 'required|exists:modules,id,deleted_at,NULL|unique:navigation,m_id,'
                    . ($request -> has('id') ? $request -> id : 'NULL') . ',id,deleted_at,NULL',
                'modal_weight' => 'required|max:1000|min:1'
            ];
        }
        $messages = [
            'm_id.required' => '请提供模块信息',
            'm_id.exists' => '该模块不存在或已被删除',
            'm_id.unique' => '该导航板块已存在',
            'weight.required' => '请输入展示权重',
            'weight.max' => '展示权重不能大于:max',
            'weight.min' => '展示权重不能小于:min',
            'modal_m_id.required' => '请提供模块信息',
            'modal_m_id.exists' => '该模块不存在或已被删除',
            'modal_m_id.unique' => '该导航板块已存在',
            'modal_weight.required' => '请输入展示权重',
            'modal_weight.max' => '展示权重不能大于:max',
            'modal_weight.min' => '展示权重不能小于:min',
        ];
        $this -> validate($request, $rules, $messages);
        if ($request -> has('id') && $request -> id > 0) {
            $exists = DB::table('navigation')
                -> where('id', $request -> id)
                -> where('m_id', $request -> m_id)
                -> whereNull('deleted_at')
                -> exists();
            if ($exists) {
                DB::table('navigation')
                    -> where('id', $request -> id)
                    -> update(['weight' => $request -> weight]);
                return redirect(route('backend.navigation')) -> with('success', '保存成功');
            } else {
                return redirect(route('backend.navigation')) -> with('error', '该导航板块不存在');
            }
        } else {
            DB::table('navigation')
                -> insert(['m_id' => $request -> modal_m_id, 'weight' => $request -> modal_weight]);
            return redirect(route('backend.navigation')) -> with('success', '保存成功');
        }
    }

    public function delete(Request $request)
    {
        if ($request -> has('id')) {
            DB::table('navigation') -> where('id', $request -> id) -> update(['deleted_at' => date('Y-m-d H:i:s')]);
            return redirect(route('backend.navigation')) -> with('success', '删除成功');
        } else {
            return redirect(route('backend.navigation')) -> with('error', '删除失败，请提供ID');
        }
    }
}
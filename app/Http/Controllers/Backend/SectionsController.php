<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 2018/11/9
 * Time: 3:41 PM
 * https://www.fushupeng.com
 * contact@fushupeng.com
 */

namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SectionsController extends Controller
{
    /**
     * 导航列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function list(Request $request)
    {
        if ($request -> has('id') && $request -> ajax()) {
            $sections = DB::table('sections')
                -> select('sections.id', 'sections.weight', 'sections.m_id', 'modules.name', 'sections.position')
                -> leftJoin('modules', 'modules.id', '=', 'sections.m_id')
                -> whereNull('sections.deleted_at')
                -> whereNull('modules.deleted_at')
                -> where('sections.id', $request -> id)
                -> first();
            if (is_null($sections)) {
                return $this -> response([], 404);
            } else {
                return $this -> response($sections, 200);
            }

        } else {
            $sections = DB::table('sections')
                -> select('sections.id', 'sections.weight', 'sections.m_id', 'modules.name', 'modules.code', 'sections.position')
                -> leftJoin('modules', 'modules.id', '=', 'sections.m_id')
                -> whereNull('sections.deleted_at')
                -> whereNull('modules.deleted_at')
                -> orderBy('weight', 'ASC')
                -> get();
            $exists_modules_id = $sections -> pluck('m_id') -> toArray();
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
            return view('backend.sections.sections', ['modules' => $modules, 'sections' => $sections]);
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
                'm_id' => 'required|exists:modules,id,deleted_at,NULL|unique:sections,m_id,'
                    . ($request -> has('id') ? $request -> id : 'NULL') . ',id,deleted_at,NULL',
                'weight' => 'required|max:1000|min:1',
                'position' => 'required|boolean'
            ];
        } else {
            $rules = [
                'modal_m_id' => 'required|exists:modules,id,deleted_at,NULL|unique:sections,m_id,'
                    . ($request -> has('id') ? $request -> id : 'NULL') . ',id,deleted_at,NULL',
                'modal_weight' => 'required|max:1000|min:1',
                'modal_position' => 'required|boolean'
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
            'position.required' => '请选择首页板块位置',
            'position.boolean' => '板块位置类型错误',
            'modal_position.required' => '请选择首页板块位置',
            'modal_position.boolean' => '板块位置类型错误',

        ];
        $this -> validate($request, $rules, $messages);
        if ($request -> has('id') && $request -> id > 0) {
            $exists = DB::table('sections')
                -> where('id', $request -> id)
                -> where('m_id', $request -> m_id)
                -> whereNull('deleted_at')
                -> exists();
            if ($exists) {
                DB::table('sections')
                    -> where('id', $request -> id)
                    -> update(['weight' => $request -> weight, 'position' => $request -> position]);
                return redirect(route('backend.sections')) -> with('success', '保存成功');
            } else {
                return redirect(route('backend.sections')) -> with('error', '该导航板块不存在');
            }
        } else {
            DB::table('sections')
                -> insert([
                    'm_id' => $request -> modal_m_id,
                    'weight' => $request -> modal_weight,
                    'position' => $request -> modal_position
                ]);
            return redirect(route('backend.sections')) -> with('success', '保存成功');
        }
    }

    public function delete(Request $request)
    {
        if ($request -> has('id')) {
            DB::table('sections') -> where('id', $request -> id) -> update(['deleted_at' => date('Y-m-d H:i:s')]);
            return redirect(route('backend.sections')) -> with('success', '删除成功');
        } else {
            return redirect(route('backend.sections')) -> with('error', '删除失败，请提供ID');
        }
    }
}
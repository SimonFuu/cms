<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 2018/11/9
 * Time: 3:40 PM
 * https://www.fushupeng.com
 * contact@fushupeng.com
 */

namespace App\Http\Controllers\Backend;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends BackendController
{
    public function links()
    {
        $linkGroups = DB::table('links')
            -> select('id', 'name', 'desc', 'weight')
            -> whereNull('deleted_at')
            -> where('parent_id', 0)
            -> orderBy('weight', 'ASC')
            -> get();
        return view('backend.settings.links.list', ['groups' => $linkGroups]);
    }

    public function linksStore(Request $request)
    {
        $rules = [
            'name' => 'required|max:10|min:1|unique:links,name,'
                . ($request -> has('id') && !is_null($request -> id) ? $request -> id : 'NULL') . ',id,deleted_at,NULL',
            'desc' => 'required|min:1|max:255',
            'weight' => 'required|numeric|min:1|max:1000'
        ];
        $messages = [
            'name.required' => '请输入导航分组名称',
            'name.max' => '导航分组名称不要多于:max',
            'name.min' => '导航分组名称不要少于:min',
            'name.unique' => '该导航分组名称已存在，请重新输入',
            'desc.required' => '请输入导航分组描述',
            'desc.max' => '导航分组描述不要多于:max',
            'desc.min' => '导航分组描述不要少于:min',
            'weight.required' => '请输入展示权重',
            'weight.numeric' => '权重格式不正确',
            'weight.min' => '权重最小值为:min',
            'weight.max' => '权重最大值为:max',
        ];
        $this -> validate($request, $rules, $messages);
        try {
            if ($request -> has('id') && !is_null($request -> id)) {
                DB::table('links') -> where('id', $request -> id)
                    -> update([
                        'name' => $request -> name,
                        'desc' => $request -> desc,
                        'weight' => $request -> weight
                    ]);
            } else {
                DB::table('links') -> insert([
                    'parent_id' => 0,
                    'name' => $request -> name,
                    'desc' => $request -> desc,
                    'weight' => $request -> weight
                ]);
            }
            return redirect(route('backend.settings.links')) -> with('success', '保存成功');
        } catch (\Exception $e) {
            return redirect(route('backend.settings.links')) -> with('error', '保存失败:' . $e -> getMessage());
        }
    }

    public function linksDelete(Request $request)
    {
        if ($request -> has('id') && !is_null($request -> id)) {
            if ($request -> id == 1) {
                return redirect(route('backend.settings.links')) -> with('error', '删除失败，系统内置部门导航分组无法删除');
            }
            $now = date('Y-m-d H:i:s');
            try {
                DB::table('links')
                    -> where('id', $request -> id)
                    -> update(['deleted_at' => $now]);
                DB::table('links')
                    -> where('parent_id', $request -> id)
                    -> update(['deleted_at' => $now]);
                return redirect(route('backend.settings.links')) -> with('success', '删除成功，分组内所有导航也一并删除!');
            } catch (\Exception $e) {
                return redirect(route('backend.settings.links')) -> with('error', '删除失败，请联系管理员: ' . $e -> getMessage());
            }

        } else {
            return redirect(route('backend.settings.links')) -> with('error', '请提供所要删除的导航分组ID');
        }

    }
    public function linksEdit(Request $request)
    {
        if ($request -> has('id')) {
            if ($request -> id == 1) {
                return redirect(route('backend.settings.links')) -> with('error', '如需修改单位内部各部门连接，请到"系统管理"-"部门管理"中进行修改');
            }
            $group = DB::table('links')
                -> select('id', 'name')
                -> whereNull('deleted_at')
                -> where('id', $request -> id)
                -> where('parent_id', 0)
                -> first();
            if (is_null($group)) {
                return redirect(route('backend.settings.links')) -> with('error', '该分组不存在或已被删除');
            }
            $links = DB::table('links')
                -> select('id', 'name', 'desc', 'link', 'weight')
                -> whereNull('deleted_at')
                -> where('parent_id', $request -> id)
                -> orderBy('weight', 'ASC')
                -> get();
            return view('backend.settings.links.group.list', ['links' => $links, 'group' => $group]);
        } else {
            return redirect(route('backend.settings.links')) -> with('error', '请提供导航分组ID');
        }
    }

    public function linksEditStore(Request $request)
    {
        $rules = [
            'parent_id' => 'required|exists:links,id,deleted_at,NULL,parent_id,0',
            'name' => 'required|min:1|max:10|unique:links,name,'
                . ($request -> has('id') && !is_null($request -> id) ? $request -> id : 'NULL') . ',id,deleted_at,NULL',
            'desc' => 'required|min:1|max:255',
            'link' => 'required|max:255|unique:links,link,'
                . ($request -> has('id') && !is_null($request -> id) ? $request -> id : 'NULL') . ',id,deleted_at,NULL',
            'weight' => 'required|numeric|min:1|max:1000'
        ];
        $messages = [
            'name.required' => '请输入导航名称',
            'name.max' => '导航名称不要多于:max',
            'name.min' => '导航名称不要少于:min',
            'name.unique' => '该导航名称已存在，请重新输入',
            'desc.required' => '请输入导航描述',
            'desc.max' => '导航描述不要多于:max',
            'desc.min' => '导航描述不要少于:min',
            'weight.required' => '请输入展示权重',
            'weight.numeric' => '权重格式不正确',
            'weight.min' => '权重最小值为:min',
            'weight.max' => '权重最大值为:max',
            'link.required' => '请输入导航链接',
            'link.max' => '导航链接不要长于:max',
            'link.min' => '导航链接不要短于:min',
            'link.unique' => '该导航链接已存在',
        ];

        $this -> validate($request, $rules, $messages);
        try {
            if ($request -> has('id') && !is_null($request -> id)) {
                DB::table('links') -> where('id', $request -> id)
                    -> update([
                        'name' => $request -> name,
                        'desc' => $request -> desc,
                        'weight' => $request -> weight,
                        'link' => $request -> link,
                        'parent_id' => $request -> parent_id
                    ]);
            } else {
                DB::table('links')
                    -> insert([
                        'name' => $request -> name,
                        'desc' => $request -> desc,
                        'weight' => $request -> weight,
                        'link' => $request -> link,
                        'parent_id' => $request -> parent_id
                    ]);
            }
            return redirect(route('backend.settings.links.edit', ['id' => $request -> parent_id])) -> with('success', '保存成功');
        } catch (\Exception $e) {
            return redirect(route('backend.settings.links.edit', ['id' => $request -> parent_id])) -> with('error', '保存失败，请联系管理员: ' . $e -> getMessage());
        }
    }

    public function linksEditDelete(Request $request)
    {

    }
}
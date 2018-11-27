<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 2018/11/26
 * Time: 2:18 PM
 * https://www.fushupeng.com
 * contact@fushupeng.com
 */

namespace App\Http\Controllers\Backend;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SpecialController extends BackendController
{
    public function list()
    {
        $existsSpecial = DB::table('modules_special')
            -> select('modules.id', 'modules.name', 'modules.desc', 'modules.thumbnail', 'modules_special.weight')
            -> leftJoin('modules', 'modules.id', '=', 'modules_special.mid')
            -> whereNull('modules.deleted_at')
            -> whereNull('modules_special.deleted_at')
            -> where('modules_special.mid', '>', 0)
            -> get();
        $ids = $existsSpecial -> pluck('id') -> toArray();
        $db_special = DB::table('modules')
            -> select('modules.id', 'modules.name')
            -> whereNotIn('id', $ids)
            -> where('type', self::SPECIAL_MODULE_TYPE)
            -> whereNull('deleted_at')
            -> orderBy('weight','ASC')
            -> get();
        $special = [];
        if ($db_special -> isNotEmpty()) {
            foreach ($db_special as $item) {
                $special[$item -> id] = $item -> name;
            }
        }
        return view('backend.special.list', ['special' => $special, 'exists' => $existsSpecial]);
    }

    public function store(Request $request)
    {
        $rules = [
            'mid' => 'required|unique:modules_special,mid,NULL,NULL,deleted_at,NULL|exists:modules,id,deleted_at,NULL,type,' . self::SPECIAL_MODULE_TYPE,
            'weight' => 'required|numeric|min:0|max:1000'
        ];
        $messages = [
            'mid.required' => '请选择板块',
            'mid.unique' => '该板块已经存在，请重新选择',
            'mid.exists' => '您选择的板块不存在或已被删除',
            'weight.required' => '请输入展示权重',
            'weight.numeric' => '权重格式不正确',
            'weight.min' => '展示权重不能少于:min',
            'weight.max' => '展示权重不能大于:max',
        ];
        $this -> validate($request, $rules, $messages);

        DB::table('modules_special')
            -> insert(['mid' => $request -> mid, 'weight' => $request -> weight]);
        return redirect(route('backend.special')) -> with('success', '保存成功');
    }

    public function layout()
    {
        $specials = DB::table('modules_special')
            -> select(
                'modules_special.weight',
                'modules_special.width',
                'modules_special.height',
                'modules_special.id',
                'modules.name',
                'modules.thumbnail'
            )
            -> leftJoin('modules', 'modules.id', '=', 'modules_special.mid')
            -> whereNull('modules.deleted_at')
            -> whereNull('modules_special.deleted_at')
            -> where('modules_special.mid', '>', 0)
            -> orderBy('modules_special.weight', 'ASC')
            -> get();
        return view('backend.special.layouts', ['specials' => $specials]);
    }

    public function layoutStore(Request $request)
    {
        if (!$request -> has('data')) {
            return $this->response([], 422, '参数缺失');
        }
        $data = collect($request -> data);
        $str = '';
        foreach ($data as $item) {
            $str .= '("' . $item['id'] . '", "' . $item['height'] . '", "' . $item['width'] . '", "' . $item['weight'] . '"),';
        }

        if ($str == '') {
            return $this -> response([], 422, '系统异常，请联系管理员');
        }
        $str = substr($str, 0, -1);
        $sql = sprintf('insert into itls_modules_special (`id`, `height`, `width`, `weight`) values %s 
                      ON DUPLICATE KEY UPDATE height=VALUES(height), weight=VALUES(weight), width = VALUES(width)', $str);
        DB::beginTransaction();
        try {
            DB::insert($sql);
            DB::commit();
            return $this -> response();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this -> response([], 500, '系统异常，请联系管理员');
        }

    }

    public function delete(Request $request)
    {
        if ($request -> has('id')) {
            DB::table('modules_special')
                -> where('mid', $request -> id)
                -> update(['deleted_at' => date('Y-m-d H:i:s')]);
            return redirect(route('backend.special')) -> with('success', '该专题已经删除下线，通过模块管理-模块设置可彻底删除该模块');
        } else {
            return redirect(route('backend.special')) -> with('error', '请提供专题ID');
        }
    }
}
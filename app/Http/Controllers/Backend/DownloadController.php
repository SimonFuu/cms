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


class DownloadController extends BackendController
{
    public function list()
    {
        $items = DB::table('download')
            -> select('id', 'name', 'desc', 'src', 'weight', 'size', 'created_at')
            -> whereNull('deleted_at')
            -> orderBy('created_at', 'desc')
            -> get();
        return view('backend.download.list', ['items' => $items]);
    }

    public function form(Request $request)
    {
        $item = null;
        if ($request -> has('id')) {
            $item = DB::table('download')
                -> select('id', 'name', 'desc', 'weight', 'src', 'size')
                -> whereNull('deleted_at')
                -> where('id', $request -> id)
                -> first();
            if (is_null($item)) {
                return redirect(route('backend.download')) -> with('error', '该对象不存在或者已被删除');
            }
        }
        return view('backend.download.form', ['item' => $item]);
    }

    /**
     *
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'desc' => 'required|max:255',
            'weight' => 'required|numeric|min:0|max:1000',
            'item' => 'required|max:255',
            'size' => 'required|max:255'
        ];
        $messages = [
            'name.required' => '请输入名称',
            'name.max' => '名称长度不要超过:max',
            'desc.required' => '请输入描述',
            'desc.max' => '描述长度不要超过:max',
            'weight.required' => '请输入权重',
            'weight.numeric' => '权重格式不正确',
            'weight.min' => '权重不能小于:min',
            'weight.max' => '权重不能大于:max',
            'item.required' => '请上传附件',
            'item.max' => '附件格式不正确，请联系管理员',
            'size.required' => '附件大小未知，请联系管理员',
            'size.max' => '附件大小异常，请联系管理员',
        ];
        $this -> validate($request, $rules, $messages);

        $data = [
            'name' => $request -> name,
            'desc' => $request -> desc,
            'weight' => $request -> weight,
            'src' => $request -> item,
            'size' => $request -> size
        ];
        if ($request -> has('id')) {
            DB::table('download')
                -> where('id', $request -> id)
                -> update($data);
        } else {
            DB::table('download') -> insert($data);
        }
        return redirect(route('backend.download')) -> with('success', '保存成功');
    }
}
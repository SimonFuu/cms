<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 2018/11/9
 * Time: 3:38 PM
 * https://www.fushupeng.com
 * contact@fushupeng.com
 */

namespace App\Http\Controllers\Backend;


use Illuminate\Http\Request;
use Auth;
use Illuminate\Validation\ValidationException;

class SignController extends BackendController
{
    public function in()
    {
        return view('backend.sign.in');
    }

    public function doIn(Request $request)
    {
        dd(Auth::user() -> id);

        $rules = [
            'username' => 'required|min:5|max:20',
            'password' => 'required|min:6|max:32'
        ];
        $messages = [
            'username.required' => '请输入用户名',
            'username.min' => '用户名长度不要少于:min',
            'username.max' => '用户名长度不要长于:max',
            'password.required' => '请输入密码',
            'password.min' => '密码长度不要少于:in',
            'password.max' => '密码长度不要长于:in',
        ];
        $this -> validate($request, $rules, $messages);

        if (Auth::attempt([$this -> username() => $request -> username, 'password' => $request -> password, 'deleted_at' => null])) {
            return redirect(route('backend.index'));
        } else {
            return $this -> sendFailedLoginResponse($request);
        }
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    private function username()
    {
        return 'username';
    }

    private function getPrivilege($uid = 0)
    {

    }
}
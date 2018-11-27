<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;


class Authenticate
{
    public function handle($request, Closure $next)
    {
        if (Auth::guest()) {
            Auth::guard()->logout();
            $request->session()->invalidate();
            return redirect(route('backend.sign.in')) -> with('error', '登录超时，请重新登录');
        } else {
            if(!$this -> checkPermission($request)) {
                # 提示没有权限
                return redirect(route('backend.index')) -> with('error', '对不起，您暂时没有访问权限！');
            }
            return $next($request);
        }
    }

    protected function checkPermission(Request $request)
    {
        $uri = substr($request -> getPathInfo(), 1);
//        dd($uri, session('permissions'));
        return isset(session('permissions')[$uri]);
    }
}

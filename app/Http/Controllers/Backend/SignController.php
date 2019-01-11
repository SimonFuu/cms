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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class SignController extends BackendController
{
    public function in()
    {
        if (Auth::guest()) {
            return view('backend.sign.in');
        } else {
            return redirect(route('backend.index'));
        }
    }

    public function doIn(Request $request)
    {
        $rules = [
            'username' => 'required|min:5|max:20',
            'password' => 'required|min:6|max:32'
        ];
        $messages = [
            'username.required' => '请输入用户名',
            'username.min' => '用户名长度不要少于:min',
            'username.max' => '用户名长度不要长于:max',
            'password.required' => '请输入密码',
            'password.min' => '密码长度不要少于:min',
            'password.max' => '密码长度不要长于:max',
        ];
        $this -> validate($request, $rules, $messages);

        if (Auth::attempt([$this -> username() => $request -> username, 'password' => $request -> password, 'deleted_at' => null])) {
            $roleIds = DB::table('roles_users')
                -> select('rid') -> whereNull('deleted_at') -> where('uid', Auth::id()) -> get();
            if ($roleIds -> isNotEmpty()) {
                $roleIds = $roleIds -> pluck('rid') -> toArray();
                $roles = $this -> getRoleActionsInfo($roleIds);
                Session::put('menus', $roles['menus']);
                Session::put('permissions', $roles['permissions']);
                return redirect(route('backend.index'));
            } else {
                Auth::guard()->logout();
                $request->session()->invalidate();
                return redirect(route('backend.sign.in')) -> with('error', '登录失败，该用户未分配角色');
            }
        } else {
            if ($request -> has('from') && $request -> from == 'index') {
                return redirect(route('backend.sign.in')) -> with('error', '登录失败，用户名或密码错误');
            } else {
                return $this -> sendFailedLoginResponse($request);
            }
        }
    }

    public function out(Request $request)
    {
        Auth::guard()->logout();

        $request->session()->invalidate();

        return redirect(route('backend.sign.in')) -> with('success', '退出登录成功');
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

    private function getPrivileges($roles = [])
    {
        $basePermissions = [
            'password',
            'password/store'
        ];

        $prefix = config('app.backend.prefix');
        $permissions = [];
        $m = [];
        $menus = [];
        $cMenus = [];  // 临时存放二级菜单 key为父级菜单的id
        $rawActions = DB::table('actions_roles')
            -> select('actions.id', 'actions.name', 'actions.des',
                'actions.menu_uri', 'actions.icon', 'actions.sub_uris', 'actions.parent_id')
            -> leftJoin('actions', 'actions_roles.aid', '=', 'actions.id')
            -> groupBy('actions.id')
            -> orderBy('actions.weight', 'ASC')
            -> whereNull('actions.deleted_at')
            -> whereNull('actions_roles.deleted_at')
            -> whereIn('actions_roles.rid', $roles)
            -> get();
        if ($rawActions) {
            foreach ($basePermissions as $permission) {
                if (strpos($permission, '/') === 0) {
                    $permissions[$prefix . $permission] = 1;
                } else {
                    $permissions[$prefix . '/' . $permission] = 1;
                }
            }
            foreach ($rawActions as $rawAction) {
                $urls = json_decode($rawAction -> sub_uris, true);
                # 获取权限
                if ($urls) {
                    foreach ($urls as $url) {
                        if (strpos($url, '/') === 0) {
                            $permissions[$prefix . $url] = 1;
                        } else {
                            $permissions[$prefix. '/' . $url] = 1;
                        }
                    }
                }
                if ($rawAction -> parent_id == 0) {
                    $m[] = [
                        'id' => $rawAction -> id,
                        'name' => $rawAction -> name,
                        'menu_uri' => (strpos($rawAction -> menu_uri, '/') === 0 ?
                            $prefix . $rawAction -> menu_uri : $prefix . '/' .  $rawAction -> menu_uri),
                        'icon' => $rawAction -> icon,
                        'childrenMenus' => []
                    ];
                } else {
                    $cMenus[$rawAction -> parent_id][] = [
                        'id' => $rawAction -> id,
                        'name' => $rawAction -> name,
                        'menu_uri' => (strpos($rawAction -> menu_uri, '/') === 0 ?
                            $prefix . $rawAction -> menu_uri : $prefix . '/' .  $rawAction -> menu_uri),
                        'icon' => $rawAction -> icon,
                    ];
                }
            }
            // 生成菜单列表（二维数组）
            $existMenuIds = [];
            foreach ($m as $key => $menu) {
                if (!isset($existMenuIds[$menu['id']])) {
                    $existMenuIds[$menu['id']] = 1;
                    $menus[$key] = $menu;
                    if (isset($cMenus[$menu['id']])) {
                        $menus[$key]['childrenMenus'] = $cMenus[$menu['id']];
                    }
                }
            }
            unset($existMenuIds);
        }
        return ['permissions' => $permissions, 'menus' => $menus];
    }
}
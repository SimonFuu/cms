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


use App\Http\Controllers\Controller;
use Illuminate\Hashing\BcryptHasher;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BackendController extends Controller
{
    protected $searchConditions = [];

    public function index()
    {
        return view('backend.index');
    }

    public function getRoleActionsInfo($roleId = 0)
    {
        $basePermissions = [
            str_replace(route('backend') . '/', '', route('backend.reset.password')),
            str_replace(route('backend') . '/', '', route('backend.upload.thumbnail')),
            str_replace(route('backend') . '/', '', route('backend.upload.ue')),
        ];
        $prefix = config('app.backend.prefix');
        $permissions = [];
        $m = [];
        $menus = [];
        $cMenus = [];  // 临时存放二级菜单 key为父级菜单的id
        if ($roleId == 0) {
            $rawActions = DB::table('actions')
                -> select('id', 'name', 'desc', 'menu_uri', 'icon', 'sub_uris', 'parent_id')
                -> orderBy('weight', 'ASC')
                -> whereNull('deleted_at')
                -> get();
        } else {
            $rawActions = DB::table('actions_roles')
                -> select('actions.id', 'actions.name', 'actions.desc',
                    'actions.menu_uri', 'actions.icon', 'actions.sub_uris', 'actions.parent_id')
                -> leftJoin('actions', 'actions_roles.aid', '=', 'actions.id')
                -> groupBy('actions.id')
                -> orderBy('actions.weight', 'ASC')
                -> whereNull('actions.deleted_at')
                -> whereNull('actions_roles.deleted_at')
                -> whereIn('actions_roles.rid', $roleId)
                -> get();
        }


        if ($rawActions -> isNotEmpty()) {
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

    protected function treeView($data = array(), $field = 'level', $id = 0, $level = 0)
    {
        $tree = [];
        foreach ($data as $value) {
            if (is_array($value)) {
                if ($value[$field] == $id) {
                    $value['level'] = $level;
                    $value['children'] = $this -> treeView($data, $field, $value['id'], $level+1);
                    $tree[] = $value;
                }
            } elseif(is_object($value)) {
                if ($value -> $field == $id) {
                    $value -> level = $level;
                    $value -> children = $this -> treeView($data, $field, $value -> id, $level+1);
                    $tree[] = $value;
                }
            }
        }
        return $tree;
    }

    protected function treeViewDepartmentsHtml($data = array(), $level = 0)
    {
        $html = '<ul class="tree-menu">';
        foreach ($data as $value) {
            $html .= '<li><a href="javascript:;" data-d-id="' . $value -> id . '">';
            $html .= '<i class="fa fa-angle-right level' . $level . '"></i>';
            $html .= '<span class="department-name">' . $value -> name . '</span></a></li>';
            if ($value -> children) {
                $html .= '<li>' . $this -> treeViewDepartmentsHtml($value -> children, $level+1) . '</li>';
            }
        }
        $html .= '</ul>';
        return $html;
    }

    protected function treeViewSearch($data = [], $id = 0)
    {
        $ids = [];
        if (!empty($data)) {
            foreach ($data as $value) {
                if ($value['parent'] == $id) {
                    $ids = array_merge($ids, [$value['id']]);
                    $ids = array_merge($ids, $this -> treeViewSearch($value['children'], $value['id']));
                } else {
                    $ids = array_merge($ids, $this -> treeViewSearch($value['children'], $id));
                }
            }
        }
        return $ids;
    }

    public function resetPassword(Request $request)
    {
        $rules = [
            'old_password' => 'required',
            'password' => 'required|min:6|max:32|confirmed'
        ];
        $message = [
            'old_password.required' => '请输入原密码',
            'password.required' => '请输入新密码',
            'password.min' => '密码长度不要少于:min',
            'password.max' => '密码长度不要超过:max',
            'password.confirmed' => '两次输入的密码不一致，请重新输入',
        ];
        $this -> validate($request, $rules, $message);

        $hasher = new BcryptHasher();
        $user = DB::table('users')
            -> select('password')
            -> whereNull('deleted_at')
            -> where('id', Auth::id())
            -> first();
        if (is_null($user)) {
            Auth::guard()->logout();
            $request->session()->invalidate();
            return redirect(route('backend.sign.in')) -> with('error', '密码修改失败，您的用户不存在或已经被删除');
        }
        if ($hasher -> check($request -> old_password, $user -> password)) {
            DB::table('users')
                -> where('id', Auth::id())
                -> update(['password' => bcrypt($request -> password)]);
            return redirect() -> back() -> with('success', '密码修改成功');
        } else {
            return redirect() -> back() -> with('error', '密码修改失败，原密码错误！');
        }

    }
}
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

class SystemController extends BackendController
{
    public function actions()
    {
        $actions = DB::table('actions')
            -> select('id', 'name', 'desc', 'menu_uri', 'sub_uris', 'weight', 'parent_id')
            -> whereNull('deleted_at')
            -> orderBy('weight', 'ASC')
            -> paginate(self::PER_PAGE_RECORD_COUNT);
        return view('backend.system.actions.list', ['actions' => $actions]);
    }

    public function actionForm(Request $request)
    {
        $action = null;
        if ($request -> has('id')) {
            $a = DB::table('actions')
                -> select('id', 'name', 'menu_uri', 'sub_uris', 'weight', 'parent_id', 'desc', 'icon')
                -> whereNull('deleted_at')
                -> where('id', $request -> id)
                -> first();
            $action = empty($a) ? null: $a;
        }
        $pActions = [0 => '一级菜单'];
        $pActs = DB::table('actions')
            -> select('id', 'name')
            -> whereNull('deleted_at')
            -> where('parent_id', 0)
            -> get();
        if (count($pActs) !== 0) {
            foreach ($pActs as $pAct) {
                $pActions['二级菜单'][$pAct -> id] = $pAct -> name;
            }
        }
        $icons = [];
        $iconsItems = DB::table('icons')
            -> select('icon') -> get();
        if ($iconsItems) {
            foreach ($iconsItems as $iconItem) {
                $icons[] = $iconItem -> icon;
            }
        }
        return view('backend.system.actions.form', ['action' => $action, 'p_actions' => $pActions, 'icons' => json_encode($icons)]);
    }

    /**
     * 保存权限
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function actionStore(Request $request)
    {
        $rules = [
            'name' => 'required|max:10|unique:actions,name,'
                . ($request -> has('id')  ? $request -> id : 'NULL') . ',id,deleted_at,NULL',
            'menu_uri' => 'required|max:255',
            'desc' => 'required|max:255',
            'sub_uris' => 'required|max:1000',
            'weight' => 'required|numeric|min:1|max:10000',
            'icon' => 'required|exists:icons,icon',
        ];
        $message = [
            'name.required' => '请输入权限名称！',
            'name.unique' => '已存在同名的权限，请确认！',
            'name.max' => '权限名称不要超过10个字符！',
            'menu_uri.required' => '请输入左侧菜单URL地址！',
            'menu_uri.max' => '长度请不要超过255！',
            'desc.required' => '请输入权限描述',
            'desc.max' => '长度不要超过255！',
            'sub_uris.required' => '请输入权限对应的URL地址，一行一个！',
            'sub_uris.max' => 'URL地址总体长度不要该超过1000！',
            'weight.required' => '请输入菜单展示权重！',
            'weight.numeric' => '菜单展示权重格式不正确，请输入1-10000的数字！',
            'weight.min' => '菜单展示权重格式不正确，请输入1-10000的数字！',
            'weight.max' => '菜单展示权重格式不正确，请输入1-10000的数字！',
            'icon.required' => '请选择菜单图标！',
            'icon.exists' => '请选择系统提供的图标！'
        ];

        $this -> validate($request, $rules, $message);
        if ($request -> has('id')) {
            $req = $request -> except(['_token', '_url']);
            if ($request -> has('sub_uris')) {
                $req['sub_uris'] = json_encode(explode("\r\n", $request -> sub_uris));
            }
            try {
                DB::table('actions')
                    -> where('id', $request -> id)
                    -> whereNull('deleted_at')
                    -> update($req);
                return redirect(route('backend.system.actions')) -> with('success', '保存成功！');
            } catch (\Exception $e) {
                return redirect(route('backend.system.actions')) -> with('error', '保存权限失败：' . $e -> getMessage());
            }
        } else {
            $req = $request -> except(['_token', '_url']);
            if ($request -> has('sub_uris')) {
                $req['sub_uris'] = json_encode(explode("\r\n", $request -> sub_uris));
            }
            try {
                DB::table('actions')
                    -> insert($req);
                return redirect(route('backend.system.actions')) -> with('success', '添加成功！');
            } catch (\Exception $e) {
                return redirect(route('backend.system.actions')) -> with('error', '添加权限失败：' . $e -> getMessage());
            }
        }
    }

    public function actionDelete(Request $request)
    {
        if (!$request -> has('id')) {
            return redirect(route('backend.system.actions')) -> with('error', '请提供权限ID');
        }
        DB::beginTransaction();
        try {
            $now = date('Y-m-d H:i:s');
            DB::table('actions')
                -> where('id', $request -> id)
                -> update(['deleted_at' => $now]);
            DB::table('actions')
                -> whereNull('deleted_at')
                -> where('parent_id', $request -> id)
                -> update(['deleted_at' => $now]);
            DB::table('actions_roles')
                -> where('aid', $request -> id)
                -> update(['deleted_at' => $now]);
            DB::commit();
            return redirect(route('backend.system.actions')) -> with('success', '删除权限成功（子权限连带一起删除）！');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect(route('backend.system.actions')) -> with('error', '删除权限失败：' . $e -> getMessage());
        }
    }

    public function departments()
    {
        $dbDepartments = DB::table('departments')
            -> select('*')
            -> whereNull('deleted_at')
            -> orderBy('weight', 'ASC')
            -> get();
        if (count($dbDepartments) > 0) {
            $departments = $this -> treeView($dbDepartments, 'parent_id');
            $departmentsHtml = $this -> treeViewDepartmentsHtml($departments);
        } else {
            $departmentsHtml = '';
        }
        return view('backend.system.departments.list', ['departmentsHtml' => $departmentsHtml]);
    }

    public function departmentForm()
    {
        $dbDepartments = DB::table('departments')
            -> select('*')
            -> whereNull('deleted_at')
            -> orderBy('weight', 'ASC')
            -> get();
        if (count($dbDepartments) > 0) {
            $departments = $this -> treeView($dbDepartments, 'parent_id');
            $departmentsHtml = $this -> treeViewDepartmentsHtml($departments);
        } else {
            $departmentsHtml = '';
        }
        return view('backend.system.departments.form', ['departmentsHtml' => $departmentsHtml]);
    }

    public function getDepartment(Request $request)
    {
        if ($request -> has('id')) {
            $department = DB::table('departments')
                -> select('id', 'name', 'weight', 'parent_id', 'desc', 'code')
                -> whereNull('deleted_at')
                -> where('id', $request -> id)
                -> first();
            if ($department) {
                if ($department -> parent_id == 0) {
                    $department -> parent_name = '毕节市纪委';
                } else {
                    $parent = DB::table('departments')
                        -> select('name')
                        -> whereNull('deleted_at')
                        -> where('id', $department -> parent_id)
                        -> first();
                    $department -> parent_name = $parent ? $parent -> name : 'NAN';
                }
                $res = ['status' => true, 'message' => '请求成功！', 'data' => $department];
            } else {
                $res = ['status' => false, 'message' => '该部门不存在或已被删除！', 'data' => []];
            }
        } else {
            $res = ['status' => false, 'message' => '请求异常，缺少关键参数！', 'data' => []];
        }
        return $res;
    }

    public function departmentStore(Request $request)
    {
        $rules = [
            'name' => 'required|max:30|unique:departments,name,'
                . ($request -> has('id') ? $request -> id : 'NULL') . ',id,deleted_at,NULL',
            'code' => 'required|max:30|unique:departments,code,'
                . ($request -> has('id') ? $request -> id : 'NULL') . ',id,deleted_at,NULL',
            'parent_id' => 'required'
                . ($request ->parent_id == 0 ? '' : '|exists:departments,id,deleted_at,NULL'),
            'weight' => 'required|numeric|min:0|max:1000',
            'desc' => 'nullable|max:255'
        ];
        $message = [
            'name.required' => '请输入部门名称！',
            'name.max' => '部门名称不要超过30个字符！',
            'name.unique' => '该部门名称已存在，请修改！',
            'code.required' => '请输入部门编码！',
            'code.max' => '部门编码不要超过30个字符！',
            'code.unique' => '该编码已经存在，请重新输入！',
            'parent_id.required' => '请选择上级部门！',
            'parent_id.exists' => '您选择的上级部门不存在，请重新选择！',
            'weight.required' => '请输入0-1000之间的数字作为部门展示权重！',
            'weight.numeric' => '请输入0-1000之间的数字作为部门展示权重！',
            'weight.min' => '请输入0-100之间的数字作为部门展示权重！',
            'weight.max' => '请输入0-100之间的数字作为部门展示权重！',
            'desc.max' => '部门描述不要超过255个字符！'
        ];
        $this -> validate($request, $rules, $message);
        try {
            if ($request -> has('id')) {
                if ($request -> id == 1) {
                    return redirect(route('backend.system.departments')) -> with('error', '修改失败，根节点无法修改，请联系管理员！');
                }
                $childrenIds = $this -> getChildrenDepartmentsAndSelf($request -> id);
                if (in_array($request -> parent_id, $childrenIds)) {
                    return redirect(route('backend.system.departments')) -> with('error', '修改失败，该部门的下级部门或自己不能作为"上级部门"！');
                } else {
                    $data = [
                        'desc' => $request -> desc,
                        'parent_id' => $request -> parent_id,
                        'weight' => $request -> weight,
                        'code' => $request -> code
                    ];
                    $department = DB::table('departments')
                        -> select('name')
                        -> whereNull('deleted_at')
                        -> where('id', $request -> id)
                        -> first();
                    if (is_null($department)) {
                        return redirect(route('backend.system.departments')) -> with('error', '修改失败：该部门不存在');
                    } else {
                        DB::beginTransaction();
                        try {
                            DB::table('departments') -> where('id', $request -> id) -> update($data);
                            DB::commit();
                            return redirect(route('backend.system.departments')) -> with('success', '修改成功！');
                        } catch (\Exception $e) {
                            DB::rollBack();
                            return redirect(route('backend.system.departments')) -> with('error', '修改失败：' . $e -> getMessage());
                        }
                    }
                }
            } else {
                $data = [
                    'name' => $request -> name,
                    'desc' => $request -> desc,
                    'parent_id' => $request -> parent_id,
                    'weight' => $request -> weight,
                    'code' => $request -> code
                ];
                DB::table('departments') -> insert($data);
                return redirect(route('backend.system.departments')) -> with('success', '添加成功！');
            }
        } catch (\Exception $e) {
            return redirect(route('backend.system.departments')) -> with('error', '保存部门失败: ' . $e -> getMessage());
        }
    }

    protected function getChildrenDepartmentsAndSelf($id = 0)
    {
        $dbDepartments = DB::table('departments')
            -> select('*')
            -> whereNull('deleted_at')
            -> orderBy('weight', 'ASC')
            -> get();
        $dps = [];
        foreach ($dbDepartments as $key => $value) {
            $dps[] = [
                'id' => $value -> id,
                'parent' => $value -> parent_id,
            ];
        }
        $departments[] = [
            'id' => '0',
            'parent' => '0',
            'level' => 0,
            'children' => $this -> treeView($dps, 'parent', 0, 1)
        ];
        $childrenIds = $this -> treeViewSearch($departments, $id);
        $childrenIds[] = $id;
        return $childrenIds;
    }

    public function departmentDelete(Request $request)
    {
        if ($request -> has('id')) {
            if ($request -> id == 1) {
                return redirect(route('backend.system.departments')) -> with('error', '删除失败，系统根节点无法删除！');
            }
            $ids = $this -> getChildrenDepartmentsAndSelf($request -> id);
            $now = date('Y-m-d H:i:s');
            if ($ids) {
                DB::beginTransaction();
                try {
                    DB::table('departments') -> whereIn('id', $ids) -> update(['deleted_at' => $now]);
                    DB::table('users') -> whereIn('dep_id', $ids) -> update(['dep_id' => 1]);
                    DB::table('departments_modules') -> whereIn('dep_id', $ids) -> update(['deleted_at' => $now]);
                    DB::commit();
                    return redirect(route('backend.system.departments')) -> with('success', '部门删除成功，部门内原有用户已移至"根节点"下！');
                } catch (\Exception $e) {
                    DB::rollBack();
                    return redirect(route('backend.system.departments')) -> with('error', '部门删除失败：' . $e -> getMessage());
                }
            } else {
                return redirect(route('backend.system.departments')) -> with('error', '部门删除失败：部门不存在');
            }
        } else {
            return redirect(route('backend.system.departments')) -> with('error', '删除失败，请提供必要参数！');
        }
    }

    public function roles()
    {
        $roles = DB::table('roles')
            -> select('id', 'name', 'desc')
            -> whereNull('deleted_at')
            -> paginate(self::PER_PAGE_RECORD_COUNT);
        return view('backend.system.roles.list', ['roles' => $roles]);
    }

    public function roleForm(Request $request)
    {
        $role = null;
        if ($request -> has('id')) {
            $roleItem = DB::table('roles')
                -> select('roles.id', 'roles.name', 'roles.desc', 'actions.id as aid')
                -> leftJoin('actions_roles', 'actions_roles.rid', 'roles.id')
                -> leftJoin('actions', 'actions.id', 'actions_roles.aid')
                -> whereNull('roles.deleted_at')
                -> whereNull('actions_roles.deleted_at')
                -> whereNull('actions.deleted_at')
                -> where('roles.id', $request -> id)
                -> get();
            if (count($roleItem) !== 0) {
                $role = (object) [];
                foreach ($roleItem as $key => $item) {
                    if ($key == 0) {
                        $role -> id = $item -> id;
                        $role -> name = $item -> name;
                        $role -> desc = $item -> desc;
                    }
                    $role -> aid[$key] = $item -> aid;
                }
            } else {
                return redirect() -> back() -> with('error', '角色状态异常，请联系管理员！');
            }
        }
        $actionsList = $this -> getRoleActionsInfo();
        $actions = $actionsList['menus'];
        return view('backend.system.roles.form', ['actions' => $actions, 'role' => $role]);
    }

    public function roleStore(Request $request)
    {
        $rules = [
            'name' => ('required|max:30|unique:roles,name,' .
                ($request -> has('id')  ? $request -> id : 'NULL') . ',id,deleted_at,0'),
            'desc' => 'required|max:255',
            'actions' => 'required|array'
        ];
        $message = [
            'name.required' => '请输入角色名称！',
            'name.max' => '角色名称长度不要超过30！',
            'name.unique' => '已存在同名的角色，请确认！',
            'desc.required' => '请输入角色描述！',
            'desc.max' => '角色描述长度不要超过255！',
            'actions.required' => '请选择权限！',
            'actions.array' => '权限格式不正确，请联系管理员！',
        ];
        $this -> validate($request, $rules, $message);
        if ($request -> has('id')) {
            $req = $request -> except(['_token', 'actions', '_url']);
            $roleActions = [];
            DB::beginTransaction();
            try {
                DB::table('roles')
                    -> where('id', $request -> id)
                    -> update($req);
                foreach ($request -> actions as $action) {
                    $roleActions[] = [
                        'rid' => $request -> id,
                        'aid' => $action
                    ];
                }
                DB::table('actions_roles')
                    -> where('rid', $request -> id)
                    -> update(['deleted_at' => date('Y-m-d H:i:s')]);
                DB::table('actions_roles')
                    -> insert($roleActions);
                DB::commit();
                return redirect(route('backend.system.roles')) -> with('success', '更新后台角色成功');
            } catch (\Exception $e) {
                DB::rollback();
                return redirect(route('backend.system.roles')) -> with('error', '更新后台失败：' . $e -> getMessage());
            }
        } else {
            $req = $request -> except(['_token', 'actions', '_url']);
            $roleActions = [];
            DB::beginTransaction();
            try {
                $rid = DB::table('roles')
                    -> insertGetId($req);
                foreach ($request -> actions as $action) {
                    $roleActions[] = [
                        'rid' => $rid,
                        'aid' => $action
                    ];
                }
                DB::table('actions_roles')
                    -> insert($roleActions);
                DB::commit();
                return redirect(route('backend.system.roles')) -> with('success', '添加后台角色成功');
            } catch (\Exception $e) {
                DB::rollback();
                return redirect(route('backend.system.roles')) -> with('error', '添加后台失败：' . $e -> getMessage());
            }
        }
    }

    public function users(Request $request)
    {
        $users = DB::table('users')
            -> select(
                'users.id', 'users.name', 'users.username', 'departments.name as dep_name'
            )
            -> leftJoin('departments', 'departments.id', '=', 'users.dep_id')
            -> whereNull('users.deleted_at')
            -> where(function ($query) use ($request){
                if ($request -> has('name') && !is_null($request -> name)) {
                    $this -> searchConditions['name'] = $request -> name;
                    $query -> where('users.name', 'like', '%' . $request -> name . '%');
                }
                if ($request -> has('username') && !is_null($request -> username)) {
                    $this -> searchConditions['username'] = $request -> username;
                    $query -> where('users.username', $request -> username);
                }
                if ($request -> has('department') && !is_null($request -> department)) {
                    $this -> searchConditions['department'] = $request -> department;
                    $query -> where('users.dep_id', $request -> department);
                }
            })
//            -> orderBy('departments.weight', 'ASC')
//            -> orderBy('users.weight', 'ASC')
            -> paginate(self::PER_PAGE_RECORD_COUNT);
        $departments = DB::table('departments')
            -> select('id', 'name')
            -> where('parent_id', 1)
            -> whereNull('deleted_at')
            -> orderBy('weight', 'ASC')
            -> get();
        $deps = [];
        foreach ($departments as $department) {
            $deps[$department -> id] = $department -> name;
        }
        return view('backend.system.users.list', ['users' => $users, 'condition' => $this -> searchConditions, 'departments' => $deps]);
    }

    public function userForm(Request $request)
    {
        $user = null;
        if ($request -> has('id')) {
            $userItem = DB::table('users')
                -> select(
                    'users.id',
                    'users.name',
                    'users.username',
                    'users.dep_id',
                    'departments.name as dep_name',
                    'roles_users.rid',
                    'users.weight'
                )
                -> leftJoin('roles_users', 'roles_users.uid', '=', 'users.id')
                -> leftJoin('departments', 'departments.id', '=', 'users.dep_id')
                -> where('users.id', $request -> id)
                -> whereNull('users.deleted_at')
                -> whereNull('roles_users.deleted_at')
                -> get();
            if ($userItem -> isEmpty()) {
                return redirect(route('backend.system.users')) -> with('error', '用户状态异常，请联系管理员');
            }
            $user = (object) $user;
            foreach ($userItem as $key => $item) {
                if ($key == 0) {
                    $user -> id = $item -> id;
                    $user -> name = $item -> name;
                    $user -> username = $item -> username;
                    $user -> dep_id = $item -> dep_id;
                    $user -> dep_name = $item -> dep_name;
                    $user -> weight = $item -> weight;
                }
                $user -> roles[$key] = $item -> rid;
            }
        }
        $roles = DB::table('roles')
            -> select('id', 'name')
            -> whereNull('deleted_at')
            -> where('id', '<>', 5) // 移除普通用户ID
            -> get();
        $dbDepartments = DB::table('departments')
            -> select('*')
            -> whereNull('deleted_at')
            -> orderBy('weight', 'ASC')
            -> get();
        if (count($dbDepartments) > 0) {
            $departments = $this -> treeView($dbDepartments, 'parent_id');
            $departmentsHtml = $this -> treeViewDepartmentsHtml($departments);
        } else {
            $departmentsHtml = '';
        }
        return view('backend.system.users.form',
            ['user' => $user, 'roles' => $roles, 'departmentsHtml' => $departmentsHtml]);
    }

    public function userStore(Request $request)
    {
        $rules = [
            'password' => ($request -> has('id') ? 'nullable|' : 'required|') . 'max:255|min:6|confirmed',
            'name' => 'required|max:30',
            'username' => 'required|max:16|min:5|unique:users,username,'
                . ($request -> has('id') ? $request -> id : 'NULL') . ',id,deleted_at,NULL',
            'dep_id' => 'required|exists:departments,id,deleted_at,NULL|gt:1',
            'roles' => 'required|array',
            'weight' => 'required|numeric|min:1|max:1000'
        ];
        $message = [
            'password.required' => '请输入密码',
            'password.max' => '密码长度最大为:max',
            'password.min' => '密码长度最短为:min',
            'password.confirmed' => '两次输入的密码不一致，请重新输入',
            'name.required' => '请输入姓名',
            'name.max' => '姓名长度最大为:max',
            'username.required' => '请输入用户名',
            'username.unique' => '该用户名已存在，请重新输入',
            'username.min' => '用户名长度最低为:min位',
            'username.max' => '用户名长度最高位:max位',
            'dep_id.required' => '请选择用户所属部门',
            'dep_id.exists' => '您选择的部门不存在或已被删除，请重试',
            'dep_id.gt' => '用户所属部门不能为毕节市纪委',
            'roles.required' => '请选择用户角色',
            'roles.array' => '用户角色格式不正确，请联系管理员',
            'weight.required' => '请输入展示权重',
            'weight.numeric' => '展示权重格式不正确',
            'weight.min' => '展示权重不能小于:min',
            'weight.max' => '展示权重不能大于:max',
        ];
        $this -> validate($request, $rules, $message);
        if ($request -> has('id')) {
            $req = $request -> except(['_token', 'roles', 'password_confirmation', '_url']);
            if ($request -> has('password') && !is_null($request -> password)) {
                $req['password'] = bcrypt($req['password']);
            } else {
                unset($req['password']);
            }
            $userRoles = [];
            
            DB::beginTransaction();
            try {
                DB::table('users')
                    -> where('id', $request -> id)
                    -> update($req);
                DB::table('roles_users')
                    -> where('uid', $request -> id)
                    -> update(['deleted_at' => date('Y-m-d H:i:s')]);
                if (!$request -> has('roles') || !$request -> roles) {
                    $userRoles = ['uid' => $request -> id, 'rid' => 5];
                } else {
                    foreach ($request -> roles as $role) {
                        $userRoles[] = [
                            'uid' => $request -> id,
                            'rid' => $role
                        ];
                    }
                }
                DB::table('roles_users')
                    -> insert($userRoles);
                DB::commit();
                return redirect(route('backend.system.users')) -> with('success', '用户信息修改成功');
            } catch (\Exception $e) {
                DB::rollback();
                return redirect(route('backend.system.users')) -> with('error', '用户信息修改失败：' . $e -> getMessage());
            }
        } else {
            $req = $request -> except(['_token', 'roles', 'password_confirmation', '_url']);
            $req['password'] = bcrypt($req['password']);
            DB::beginTransaction();
            try {
                $id = DB::table('users')
                    -> insertGetId($req);
                $userRoles = [];
                foreach ($request -> roles as $role) {
                    $userRoles[] = [
                        'uid' => $id,
                        'rid' => $role
                    ];
                }
                DB::table('roles_users')
                    -> insert($userRoles);
                DB::commit();
                return redirect(route('backend.system.users')) -> with('success', '添加人员成功');
            } catch (\Exception $e) {
                DB::rollback();
                return redirect(route('backend.system.users')) -> with('error', '添加人员失败：' . $e -> getMessage());
            }
        }
    }
}
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


use Illuminate\Support\Facades\DB;

class SettingsController extends BackendController
{
    public function navLinks()
    {
        DB::table('links')
            -> select('id', 'name')
            -> whereNull('deleted_at')
            -> where('parent_id', 0)
            -> orderBy('weight')
            -> get();

    }
}
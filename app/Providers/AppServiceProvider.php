<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        if ($request -> method() == 'GET') {
            $uri = $request -> getPathInfo();
            $uriArray = explode('/', $uri);
            if(isset($uriArray[1]) && $uriArray[1] == 'admin') {
                # 管理后台
                $this -> backendSidebarGenerator($request);
            } else {
                # 前端
                $this -> frontendCommonGenerate();
            }
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    private function frontendCommonGenerate()
    {
        $navs = DB::table('navigation')
            -> select('modules.name', 'modules.code', 'modules.type')
            -> leftJoin('modules', 'modules.id', '=', 'navigation.m_id')
            -> whereNull('navigation.deleted_at')
            -> orderBy('navigation.weight', 'ASC')
            -> get();
        view() -> composer('frontend.default.layouts.navs', function ($view) use ($navs) {
            $view -> with('navs', $navs);
        });
    }

    private function backendSidebarGenerator(Request $request)
    {
        view() -> composer('backend.layouts.sidebar', function ($view) use ($request) {
            $session = session('menus');
            $menus = [];
            foreach ($session as $item) {
                $item['active'] = false;
                if ($item['childrenMenus']) {
                    foreach ($item['childrenMenus'] as $key => $child) {
                        $item['childrenMenus'][$key]['active'] = false;
                        if (strpos($request -> getPathInfo(), $child['menu_uri']) !== false) {
                            $item['active'] = true;
                            $item['childrenMenus'][$key]['active'] = true;
                        }
                        $menu = $item;
                    }
                    $menus[] = $menu;
                } else {
                    if (strpos($request -> getPathInfo(), $item['menu_uri']) !== false) {
                        $item['active']= true;
                    }
                    $menus[] = $item;
                }
            }
            $view -> with('menus' , $menus);
        });
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
// Http
use Illuminate\Http\Request;
// Routing
use Illuminate\Routing\Controller as BaseController;
// Support
use Illuminate\Support\Facades\Route;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public $hasNoPermissions = false;

    public function __construct(Request $request)
    {
        if ( $request->header('hasNoPermissions') ) {
            $this->hasNoPermissions = true;
        }
        $this->middleware(function ($request, $next) {
            if (request()->is('*dashboard*') && !request()->is('*api*') && !str_contains(Route::currentRouteName(), 'home')) {
                if(auth()->check()) {



                    $routeName = Route::currentRouteName();
                    $routeName = substr($routeName, 6);
                    $routeName = preg_replace('/\bstore\b/u', 'create', $routeName);
                    $routeName = preg_replace('/\bupdate\b/u', 'edit', $routeName);
                    if(count(explode(".",$routeName)) > 2) {
                        $arr = explode(".",$routeName);
                        $routeName = $arr[1].".".$arr[2];
                    }

                    // dd($routeName);

                    if(!isAdmin()) {
                        if(is_null(auth()->user()->accepted_at)) {
                            if(!is_null(auth()->user()->rejected_at)) {
                                if(!in_array($routeName,["clubs.edit","notifications.index","notifications.show"])) {
                                    return abort(403, 'Unauthorized action');
                                }
                            } else {
                                return abort(403, 'Unauthorized action');
                            }
                        }
                    }

                    if(! $this->hasNoPermissions
                        && !str_contains($routeName, 'profile')
                        && !str_contains($routeName, 'change_password')
                        && !str_contains($routeName, 'branches.getAll')
                        && !str_contains($routeName, 'club.export')
                        && !str_contains($routeName, 'countries.getCities')
                        && !str_contains($routeName, 'orders.change-status')
                        && !str_contains($routeName, 'categories.getAll')
                        && !str_contains($routeName, 'categories.child')
                        && !str_contains($routeName, 'export.excel')
                        && !str_contains($routeName, 'export.pdf')
                        && !str_contains($routeName, 'requests.reject')
                        && !str_contains($routeName, 'staffs.getAll')
                        && !str_contains($routeName, 'images.destroy')
                        && !auth()->user()->hasAnyPermission($routeName)) {
                        return abort(403, 'Unauthorized action');
                    }
                }
            }
            return $next($request);
        })->except('logout');
    }
}

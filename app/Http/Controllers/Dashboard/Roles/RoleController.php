<?php

namespace App\Http\Controllers\Dashboard\Roles;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\Dashboard\Roles\UpdateRequest;
use App\Http\Requests\Dashboard\Roles\CreateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = [
            'title' =>  __("Roles Lists"),
            'items' =>  [
                [
                    'title' =>  __("Roles Lists"),
                    'url'   =>  '#!',
                ]
            ],
        ];
        if(env("APP_DEBUG") === false) {
            $lists = Role::whereNotIn("id",[1,2,3,4])->get();
        } else {
            $lists = Role::all();
        }
        return view('admin.pages.roles.index',compact('breadcrumb', 'lists'));
    }

    public function create() {
        $breadcrumb = [
            'title' =>  __("Create New Role"),
            'items' =>  [
                [
                    'title' =>  __("Roles Lists"),
                    'url'   =>  route("admin.roles.index"),
                ],
                [
                    'title' =>  __("Create New Role"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        return view("admin.pages.roles.edit",get_defined_vars());
    }

    public function store(CreateRequest $request) {
        $data = $request->validated();
        $role = Role::create($data);
        $permissions = [];
        foreach($request->permissions ?? [] as $permission) {
            $permissions[] = Permission::firstOrCreate(['name' => $permission]);
        }
        $role->syncPermissions($permissions);
        return Redirect::route('admin.roles.index')->with('success', __(":item has been created.", ['item' => __('Role')]));
    }

    public function edit(Role $role)
    {
        if(env("APP_DEBUG") === false) {
            if(in_array($role->id,[1,2,3,4])) {
                abort(404);
            }
        }
        $breadcrumb = [
            'title' =>  __("Edit Role"),
            'items' =>  [
                [
                    'title' =>  __("Roles Lists"),
                    'url'   => route('admin.roles.index'),
                ],
                [
                    'title' =>  __("Edit Role"),
                    'url'   =>  '#!',
                ],
            ],
        ];
        $permissions = $role->permissions->pluck('name')->toArray();

        return view('admin.pages.roles.edit',compact('breadcrumb', 'role', 'permissions'));
    }

    public function update(UpdateRequest $request, Role $role)
    {
        $data = $request->validated();
        $role->update($data);
        $permissions = [];
        foreach($data['permissions'] ?? [] as $permission) {
            $permissions[] = Permission::firstOrCreate(['name' => $permission]);
        }
        $role->syncPermissions($permissions);
        return Redirect::route('admin.roles.index')->with('success', __(":item has been updated.", ['item' => __('Role')]));
    }

    public function destroy(Role $role)
    {
        if(in_array($role->id,[1,2,3,4])) {
            return Redirect::route('admin.roles.index')->with('success',  __("This :item Can't deleted",['item' => __('Role')]) );
        }
        $role->delete();
        return Redirect::route('admin.roles.index')->with('success',  __(":item has been deleted",['item' => __('Role')]) );
    }
}

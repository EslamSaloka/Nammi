<?php

namespace Database\Seeders;

use App\Models\Time;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Support\UserPermissions;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rolesArray       = (new UserPermissions)->getRoles();
        $permissions      = (new UserPermissions)->getPermissions();
        $checkPermissions = false;
        if(Role::count() == 0) {
            foreach($rolesArray as $role) {
                $newRole = Role::create([
                    "name"          => $role,
                    "guard_name"    => "web",
                ]);
                if($checkPermissions === false) {
                    $getPermissions = [];
                    foreach ($permissions as $permission) {
                        if(is_array($permission)) {
                            foreach($permission as $p) {
                                $getPermissions[] = Permission::firstOrCreate(['name' => $p]);
                            }
                        } else {
                            $getPermissions[] = Permission::firstOrCreate(['name' => $permission]);
                        }
                    }
                    $checkPermissions == true;
                }
            }
        }

        // ============================================ //
        // Admin Permissions
        Role::where("name",User::TYPE_ADMIN)->first()->syncPermissions($permissions);
        // Customer Permissions
        Role::where("name",User::TYPE_CUSTOMER)->first()->syncPermissions([]);
        // Club Permissions
        Role::where("name",User::TYPE_CLUB)->first()->syncPermissions((new User)->clubsPermissions());
        // ============================================ //
        // Staff Permissions
        Role::where("name",User::TYPE_BRANCH)->first()->syncPermissions((new User)->staffPermissions());
        // ============================================ //


        $admin = User::where([
            "email" => "admin@admin.com"
        ])->first();
        if(is_null($admin)) {
            $admin = User::create([
                'name'              =>  $rolesArray[0],
                "email"             =>  "admin@admin.com",
                'phone'             =>  "966512345678",
                'phone_verified_at' =>  Carbon::now(),
                'password'          =>  Hash::make("admin@123456"),
                'time_id'           =>  Time::first()->id ?? 0,
            ]);
        }
        $admin->assignRole($rolesArray[0]);
    }
}

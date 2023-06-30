<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $roleUser = Role::updateOrCreate(['name' => 'Customer']);
        $roleGuest = Role::updateOrCreate(['name' => 'Guest']);
        $roleAdmin = Role::updateOrCreate(['name' => 'Admin']);
        $roleIotAdmin = Role::updateOrCreate(['name' => 'IotAdmin']);

        $userAdmin = User::updateOrCreate(
                        [
                            'name' => 'Admin',
                            'email' => 'admin@admin.com',
                        ],
                        [
                            'password' => bcrypt('admin123')
        ]);
        
        $userIotAdmin = User::updateOrCreate(
                        [
                            'name' => 'IOT Admin',
                            'email' => 'iot@admin.com',
                        ],
                        [
                            'password' => bcrypt('admin123')
        ]);

        $user = User::updateOrCreate(
                        [
                            'name' => 'User',
                            'email' => 'user@user.com',
                        ],
                        [
                            'password' => bcrypt('admin123')
        ]);
        $userAdmin->assignRole($roleAdmin);
        $userIotAdmin->assignRole($roleIotAdmin);
        $user->assignRole($roleUser);
    }

}

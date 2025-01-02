<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'manage categories',
            'manage packages',
            'manage transaction',
            'manage package banks',
            'checkout package',
            'view order'
        ];

        foreach($permissions as $permission ){
            Permission::firstOrCreate([
                'name' => $permission
            ]);
        }


        $customerRole = Role::firstOrCreate([
            'name' => 'customer'
        ]);

        $customerPermissions = [
            'checkout package',
            'view order',
        ];

        $customerRole->syncPermissions($customerPermissions);


        $superAdminRole = Role::firstOrCreate([
            'name' => 'super_admin'
        ]);

        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'super@admin.com',
            'phone_number' => '6287880785245',
            'avatar' => 'images/default-avatar.png',
            'password' => bcrypt('securepassword')

        ]);

    }
}

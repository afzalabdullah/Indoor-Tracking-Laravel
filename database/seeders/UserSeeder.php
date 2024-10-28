<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $user_list=Permission::create(['name'=>'users.list']);
        $user_view=Permission::create(['name'=>'users.view']);
        $user_create=Permission::create(['name'=>'users.create']);
        $user_edit=Permission::create(['name'=>'users.edit']);
        $user_delete=Permission::create(['name'=>'users.delete']);
        $admin_role= Role::create(['name'=>'admin']);
        $admin_role->givePermissionTo([
            $user_list,
            $user_view,
            $user_create,
            $user_edit,
            $user_delete,
        ]);
        $admin= User::create([
            'name'=>'mansoor',
            'email'=>'mansoor@gmail.com',
            'password'=>bcrypt('password')
        ]);
        $admin->assignRole($admin_role);

        $user= User::create([
            'name'=>'haris',
            'email'=>'haris@gmail.com',
            'password'=>bcrypt('password')
        ]);
        $user_role= Role::create(['name'=>'intern']);
        $user_role->givePermissionTo([
            $user_list
        ]);
        $user->assignRole($user_role);
    }
}

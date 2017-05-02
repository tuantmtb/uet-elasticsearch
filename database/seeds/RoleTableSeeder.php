<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // permission
        $this->createPermission();

        // role and set permission
        $this->createRole();

    }

    /**
     * create permission
     */
    public function createPermission()
    {
        Permission::create([
            'name' => 'manage'
        ]);

        Permission::create([
            'name' => 'edit'
        ]);
    }

    public function createRole()
    {
        $manage = Permission::findByName('manage');
        $edit = Permission::findByName('edit');

        $admin = Role::create([
            'name' => 'admin',
            'display_name' => 'Quản trị'
        ]);

        $admin->attachPermission($manage);
        $admin->attachPermission($edit);

        Role::create([
            'name' => 'editor',
            'display_name' => 'Biên soạn nội dung'
        ])->attachPermission($edit);

        Role::create([
            'name' => 'author',
            'display_name' => 'Tác giả'
        ])->attachPermission($edit);
    }
}

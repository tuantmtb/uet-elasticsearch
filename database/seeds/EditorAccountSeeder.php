<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class EditorAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $editor = Role::findByName('editor');

        User::create([
            'name' => 'Ngô Quang Mạnh',
            'email' => 'ngoquangmanh97@gmail.com',
            'password' => bcrypt('editor'),
            'remember_token' => str_random(10),
        ])->attachRole($editor);

        User::create([
            'name' => 'Phùng Phương',
            'email' => 'phungphuong97@gmail.com',
            'password' => bcrypt('editor'),
            'remember_token' => str_random(10),
        ])->attachRole($editor);

        User::create([
            'name' => 'Dương Khánh Nghĩa',
            'email' => 'nghia9dth@gmail.com',
            'password' => bcrypt('editor'),
            'remember_token' => str_random(10),
        ])->attachRole($editor);

        User::create([
            'name' => 'Nguyễn Thị Phượng',
            'email' => 'phuong544@gmail.com',
            'password' => bcrypt('editor'),
            'remember_token' => str_random(10),
        ])->attachRole($editor);

        User::create([
            'name' => 'Nguyễn Thị Huyền',
            'email' => 'nguyenhuyen98pm@gmail.com',
            'password' => bcrypt('editor'),
            'remember_token' => str_random(10),
        ])->attachRole($editor);

        User::create([
            'name' => 'Nguyễn Việt Hoàng',
            'email' => 'ngviethoang0212@gmail.com',
            'password' => bcrypt('editor'),
            'remember_token' => str_random(10),
        ])->attachRole($editor);

        User::create([
            'name' => 'Nguyễn Thị Quỳnh',
            'email' => 'quynhkun15101998@gmail.com',
            'password' => bcrypt('editor'),
            'remember_token' => str_random(10),
        ])->attachRole($editor);

        User::create([
            'name' => 'Đặng Thị Tuyết',
            'email' => 'dttuyet2711@gmail.com',
            'password' => bcrypt('editor'),
            'remember_token' => str_random(10),
        ])->attachRole($editor);

        User::create([
            'name' => 'Vũ Thị Thu Hà',
            'email' => 'thuhakiki96@gmail.com',
            'password' => bcrypt('editor'),
            'remember_token' => str_random(10),
        ])->attachRole($editor);

        User::create([
            'name' => 'Võ Đình Hiếu',
            'email' => 'vdhieu@gmail.com',
            'password' => bcrypt('editor'),
            'remember_token' => str_random(10),
        ])->attachRole($editor);

        User::create([
            'name' => 'Nguyễn Quang Hà',
            'email' => 'loveparadise98@gmail.com',
            'password' => bcrypt('editor'),
            'remember_token' => str_random(10),
        ])->attachRole($editor);

        User::create([
            'name' => 'Thu Hằng',
            'email' => 'thuhang1906199735@gmail.com',
            'password' => bcrypt('editor'),
            'remember_token' => str_random(10),
        ])->attachRole($editor);

        User::create([
            'name' => 'Minh Hằng',
            'email' => 'minhhang25071997@gmail.com',
            'password' => bcrypt('editor'),
            'remember_token' => str_random(10),
        ])->attachRole($editor);

        User::create([
            'name' => 'Tài Khoản Hệ Thống',
            'email' => 'vci-editor@gmail.com',
            'password' => bcrypt('editor'),
            'remember_token' => str_random(10),
        ])->attachRole($editor);




    }
}

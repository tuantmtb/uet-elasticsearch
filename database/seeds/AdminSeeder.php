<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::findByName('admin');
        User::create([
            'name' => 'Trần Minh Tuấn',
            'email' => 'tuantmtb@gmail.com',
            'password' => bcrypt('admin'),
            'remember_token' => str_random(10),
        ])->attachRole($admin);

        User::create([
            'name' => 'Nguyễn Văn Nhật',
            'email' => 'nguyenvannhat152@gmail.com',
            'password' => bcrypt('admin'),
            'remember_token' => str_random(10),
        ])->attachRole($admin);

        User::create([
            'name' => 'Nguyễn Trung',
            'email' => 'trungnd@gmail.com',
            'password' => bcrypt('admin'),
            'remember_token' => str_random(10),
        ])->attachRole($admin);

        $editor = Role::findByName('editor');
        User::create([
            'name' => 'Võ Đình Hiếu',
            'email' => 'hieuvd@vnu.edu.vn',
            'password' => bcrypt('admin'),
            'remember_token' => str_random(10),
        ])->attachRole($admin);

        $author = Role::findByName('author');
        User::create([
            'name' => 'Nguyễn Việt Anh',
            'email' => 'anhnv@vnu.edu.vn',
            'password' => bcrypt('admin'),
            'remember_token' => str_random(10),
        ])->attachRole($author);

        User::create([
            'name' => 'Bảo Ngọc',
            'email' => 'baongoc124@gmail.com',
            'password' => bcrypt('admin'),
            'remember_token' => str_random(10),
        ])->attachRole($admin);

        User::create([
            'name' => 'Tài khoản thử nghiệm',
            'email' => 'guest@vnu.edu.vn',
            'password' => bcrypt('dhqghn'),
            'remember_token' => str_random(10),
        ]);

    }
}

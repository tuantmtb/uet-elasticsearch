<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\Author::class, function (Faker\Generator $faker) {

    $ho = ['Trần', 'Bùi', 'Nguyễn', 'Lê', 'Vũ', 'Phạm', 'Phan', 'Đặng', 'Đỗ'];
    $dem_nam = ['Minh', 'Văn', 'Việt', 'Hoàng'];
    $ten_nam = ['Tuấn', 'Nhật', 'Quang', 'Hải', 'Thắng', 'Hiếu', 'Cường', 'Huy', 'Hậu', 'Long', 'Hiệp',
        'Hùng', 'Nam', 'Phong', 'Hiển', 'Hoàn', 'Sơn', 'Thành', 'Thăng', 'Công'];

    $dem_nu = ['Thị', 'Bích', 'Thúy', 'Thùy', 'Ngọc'];
    $ten_nu = ['Dung', 'Hạnh', 'Phượng ', 'Nga', 'Tuyết', 'Huyền', 'Nhung', 'Thảo', 'Trà', 'Yến', 'Hà', 'Hương', 'Lan', 'Linh', 'Hằng'];

    if ($faker->randomElement([true, false])) {
        $hoten = $faker->randomElement($ho) . ' ' . $faker->randomElement($dem_nam) . ' ' . $faker->randomElement($ten_nam);
    } else {
        $hoten = $faker->randomElement($ho) . ' ' . $faker->randomElement($dem_nu) . ' ' . $faker->randomElement($ten_nu);

    }

    return [
        'name' => $hoten,
    ];
});
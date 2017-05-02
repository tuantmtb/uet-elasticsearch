<?php

use App\Models\Journal;
use App\Models\Organize;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class CreateRecordDefault extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $journal = Journal::create([
            'name' => 'Chưa phân loại'
        ]);

        $organize = Organize::create([
            'name' => 'Chưa phân loại'
        ]);

    }
}

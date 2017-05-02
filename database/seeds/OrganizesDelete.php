<?php

use App\Models\Organize;
use Illuminate\Database\Seeder;

class OrganizesDelete extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $organizes = Organize::all();
        foreach ($organizes as $organize) {
            $organize->authors()->detach();
            $organize->delete();
        }

    }
}

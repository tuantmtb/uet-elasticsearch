<?php

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json_path = base_path('resource_doc/dump/subjects.json');
        $results = json_decode(file_get_contents($json_path), true);
        foreach ($results as $result) {
            Subject::create($result);
        }
    }
}

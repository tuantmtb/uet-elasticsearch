<?php

use App\Models\Organize;
use Illuminate\Database\Seeder;

class OrganizesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createGroupUniversityAtFolder("resource_doc/university/group");
        $this->createSingleUniversity("resource_doc/university/single_university.json");
    }

    private function createSingleUniversity($filePath)
    {
        $json_path = base_path($filePath);
        $results = json_decode(file_get_contents($json_path), true);
        foreach ($results as $result) {
            Organize::create(['name' => $result]);
        }
    }

    private function createGroupUniversityAtFolder($folderPath)
    {
        $filesInFolder = File::allFiles($folderPath);
        foreach ($filesInFolder as $path) {
            try {
                $pathLink = pathinfo($path);
                $link = $pathLink["dirname"] . "/" . $pathLink["basename"];
                $json_path = base_path($link);
                $results = json_decode(file_get_contents($json_path), true);
                $organizeParent = Organize::create(['name' => $results[0]]);
                foreach ($results as $i => $result) {
                    if ($i != 0) {
                        $organizeChild = Organize::create(['name' => $result]);
                        $organizeChild->appendToNode($organizeParent)->save();
                    }
                }
            } catch (Exception $e) {
                $e->getMessage();
            }
        }
    }
}

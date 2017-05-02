<?php

use App\Models\Organize;
use Illuminate\Database\Seeder;

class OrgainesVASTSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createOrganizeAtFolder("resource_doc/university/vien");
    }

    private function createOrganizeAtFolder($folderPath)
    {
        $filesInFolder = File::allFiles($folderPath);
        foreach ($filesInFolder as $path) {
            try {
                $pathLink = pathinfo($path);
                $link = $pathLink["dirname"] . "/" . $pathLink["basename"];
                $json_path = base_path($link);
                $results = json_decode(file_get_contents($json_path), true);

                $organizeParent = Organize::create(['name' => $results["vi"][0], 'name_en' => $results["en"][0]]);

                foreach ($results["vi"] as $i => $result) {
                    if ($i != 0) {
                        $organizeChild = Organize::create(['name' => $results["vi"][$i], 'name_en' => $results["en"][$i]]);
                        $organizeChild->appendToNode($organizeParent)->save();
                    }
                }
            } catch (Exception $e) {
                $e->getMessage();
            }
        }
    }
}

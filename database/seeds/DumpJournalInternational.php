<?php

use App\Models\Article;
use App\Models\Journal;
use App\Models\ArticleRelation;
use Illuminate\Database\Seeder;

class DumpJournalInternational extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->dumpBatchJournalsInternational("resource_doc/dump/journal/journal_international/journal_international.json");
    }

    private function dumpBatchJournalsInternational($filePath)
    {
        $json_path = base_path($filePath);
        $results = json_decode(file_get_contents($json_path), true);

//        dd($results);
        foreach ($results as $result) {
//            dd($result);
            $journalInternational = \App\Models\JournalInternational::create([
                'title' => $result["Title"],
                'publisher' => $result["Publisher"],
                'issn' => $result["ISSN"],
                'eissn' => $result["E-ISSN"],
                'country' => $result["Country"],
                'type' => $result["Type"]
            ]);
        }

    }

}

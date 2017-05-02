<?php

use Illuminate\Database\Seeder;

class vjol_dump_db extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filePath_vjol = 'resource_doc/dump/pubs.json';
        $this->importArticleBatch($filePath_vjol);

//        $filePath_vjs_ac_vn = 'resource_doc/dump/vjs-total-final.json'; // old format before 18-02-2017
//        $this->importArticleBatch($filePath_vjs_ac_vn);

    }

    private function importArticleBatch($filePath)
    {
        $json_path = base_path($filePath);
        $results = json_decode(file_get_contents($json_path), true);

        foreach ($results as $result) {
            $journal = $this->findOrCreateJournal($result["journalName"]);
            $article = App\Models\Article::create([
                'title' => $result["title"],
                'abstract' => $result["abstracts"],
                'uri' => $result["uri"],
                'author' => $result["authors"],
                'volume' => $result["volume"],
                'number' => $result["number"],
                'year' => $result["year"],
                'source' => $result["source"],
                'usable' => $result["usable"],
                'journal_id' => $journal->id,
            ]);
            $article->save();
        }
    }

    private function findOrCreateJournal($journalname)
    {
        $journals = \App\Models\Journal::whereName($journalname);
        if ($journals != null && $journals->count() > 0) {
            // found journal by name
            $journal = $journals->first();
            return $journal;
        } else {
            // create journal
            $journal = \App\Models\Journal::create([
                'name' => $journalname
            ]);
            return $journal;
        }
    }
}

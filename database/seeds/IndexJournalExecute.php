<?php

use App\Http\Controllers\Api\IndexingElasticsearchApiController;
use App\Models\Journal;
use Illuminate\Database\Seeder;

class IndexJournalExecute extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->indexJournals();
    }

    public function indexJournals()
    {
        $journals = Journal::orderBy('updated_at', 'desc')->get();

        foreach ($journals as $journal) {
            try {
                $id = $journal->id;

                $ESindexing = new IndexingElasticsearchApiController();

                $params = [
                    'index' => 'test',
                    'type' => 'journal',
                    'id' => $id,
                    'body' => $ESindexing->getInfoJournal($id)
                ];

                $client = VciQueryES::getClientESIndex();
                if ($client != null) {
                    $response = VciQueryES::getClientESIndex()->index($params);
                    \Log::debug('indexed journal id= : ' . $id);
                    \Log::debug('res journal : ' , $response);
                }

            } catch (\Exception $e) {
                \Log::debug('indexjournals exception: ' . $e);
            }
        }
        \Log::debug('indexjournals done ');
    }
}

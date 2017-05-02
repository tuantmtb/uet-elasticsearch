<?php

use App\Http\Controllers\Api\IndexingElasticsearchApiController;
use App\Models\Organize;
use Illuminate\Database\Seeder;

class IndexOrganizeExecute extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->indexOrganizes();
    }

    public function indexOrganizes()
    {
        $organizes = Organize::orderBy('updated_at', 'desc')->get();

        foreach ($organizes as $organize) {
            try {
                $id = $organize->id;

                $ESindexing = new IndexingElasticsearchApiController();

                $params = [
                    'index' => 'test',
                    'type' => 'organize',
                    'id' => $id,
                    'body' => $ESindexing->getInfoOrganize($id)
                ];

                $client = VciQueryES::getClientESIndex();
                if ($client != null) {
                    $response = VciQueryES::getClientESIndex()->index($params);
                    \Log::debug('indexed organize id = : ' . $id);
                }

            } catch (\Exception $e) {
                \Log::debug('indexorganizes exception: ' . $e);
            }
        }
        \Log::debug('indexorganizes done ');
    }
}

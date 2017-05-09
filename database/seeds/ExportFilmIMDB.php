<?php

use App\Models\Role;
use App\Models\User;
use Elasticsearch\ClientBuilder;
use Illuminate\Database\Seeder;

class ExportIMDB extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->filmInfoAll();
    }

    private function filmInfoAll()
    {
        $size = 100;
        $count = 0;
        for ($offset = 0; $offset < 90556; $offset++) {
            $o = $offset * $size;
            $films = DB::select(DB::raw("
          SELECT id, title, imdb_index, production_year, info, movie_id 
          from film_index
          limit  $o, $size 
         "));
            foreach ($films as $film) {
                $count++;
                Log::debug($count . ' - id= ' . $film->id);
                $this->indexFilmElasticsearch($film->id, 'film_all', $film);
            }
        }
    }

    public function indexFilmElasticsearch($id, $type, $data)
    {
        try {
            $params = [
                'index' => 'imdb',
                'type' => $type,
                'id' => $id,
                'body' => $data
            ];
            $client = ClientBuilder::create()
                ->setHosts(['112.137.131.9:9200'])// Set the hosts
                ->build();

            $response = $client->index($params);
            Log::debug('response: ', $response);
        } catch (\Exception $e) {

        }
    }

}

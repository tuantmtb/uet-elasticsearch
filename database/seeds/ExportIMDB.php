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
        $size = 100;
        $count = 0;
        for ($offset = 0; $offset < 90556; $offset++) {
            $o = $offset * $size;
            $films = DB::select(DB::raw("
          SELECT title, imdb_index, production_year, info, movie_id 
          from film_index
          limit  $o, $size 
         "));
            foreach ($films as $film) {
                $count++;
                Storage::disk('local')->put($film->movie_id . '.json', json_encode($film));
                Log::debug($count . ' - id= ' . $film->movie_id);
                $this->indexFilmElasticsearch($film->movie_id, $film);
            }
        }
    }

    public function indexFilmElasticsearch($id, $film)
    {
        try {
            $params = [
                'index' => 'imdb',
                'type' => 'film',
                'id' => $id,
                'body' => $film
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

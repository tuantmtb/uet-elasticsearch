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
//        $this->filmInfo();
        $this->person();
    }

    private function filmInfo()
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
                $this->indexFilmElasticsearch($film->movie_id, 'film', $film);
            }
        }
    }

    public function indexFilmElasticsearch($id, $type, $data)
    {
        try {
            $hosts = config('settings.elastic_search_ips');
            $params = [
                'index' => 'imdb',
                'type' => $type,
                'id' => $id,
                'body' => $data
            ];
            $client = ClientBuilder::create()
                ->setHosts($hosts)// Set the hosts
                ->build();

            $response = $client->index($params);
            Log::debug('response: ', $response);
        } catch (\Exception $e) {

        }
    }

    private function person()
    {
        $size = 100;
        $count = 107175;
        for ($offset = 107175; $offset < 6092031; $offset++) {
            $o = $offset * $size;
            $authors = DB::select(DB::raw("
          SELECT id, name, imdb_index, imdb_id, gender 
          from name
          limit  $o, $size 
         "));
            foreach ($authors as $author) {
                $count++;
                Storage::disk('local')->put('author/' . $author->id . '.json', json_encode($author));
                Log::debug($count . ' - id= ' . $author->id);
                $this->indexFilmElasticsearch($author->id, 'author', $author);
            }
        }
    }
}

<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $this->call(RoleTableSeeder::class); //seeded
//        $this->call(AdminSeeder::class); //seeded
//        $this->call(EditorAccountSeeder::class); //seeded
//        $this->call(CreateRecordDefault::class); //seeded
//        $this->call(OrganizesDelete::class); //seeded
//        $this->call(OrganizesSeeder::class); //seeded
//        $this->call(OrgainesVASTSeeder::class); //seeded

        $this->call(js_dump_db::class); //real import vjs format after 17022017 //seeded
//        $this->call(dumpOJSExport::class); // real ojs export standard //seeded
//        $this->call(vjol_dump_db::class); //real import vjol format before 17022017 //seeded

//        $this->call(CreateManualJournalSeeder::class);

//        $this->call(DumpRelationArticle::class); //real

//        $this->call(ExtractAuthors::class); //real extract authors from articles // deprecated

//        $this->call(NormalizerDatabaseSeeder::class); //real delete smt


    }
}

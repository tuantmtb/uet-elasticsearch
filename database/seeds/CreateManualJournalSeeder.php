<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class CreateManualJournalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $createrModel = new CreaterModelHelper();
        $createrModel->findOrCreateJournal("Advances in Natural Sciences: Nanoscience and Nanotechnology"); //http://iopscience.iop.org/journal/2043-6262
        $createrModel->findOrCreateJournal("Journal of Science: Advanced Materials and Devices"); //https://www.journals.elsevier.com/journal-of-science-advanced-materials-and-devices/
        $createrModel->findOrCreateJournal("Vietnam Journal of Mathematics"); //http://www.math.ac.vn/publications/vjm/
    }
}

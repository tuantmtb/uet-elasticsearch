<?php

use Illuminate\Database\Seeder;
use SoapBox\Formatter\Formatter;
use App\Models\Journal;
use App\Models\Article;
use App\Models\Author;
use App\Models\Organize;

class CreaterModelHelper
{

    /**
     * Create journals if non exist
     * @param $journalname
     * @return array|\Illuminate\Database\Eloquent\Model|null|stdClass|static
     */
    public function findOrCreateJournal($journalname)
    {
        $journalname = trim($journalname);
        if ($journalname != "") {
            $journals = Journal::whereName($journalname);
            if ($journals != null && $journals->count() > 0) {
                // found journal by name
                $journal = $journals->first();
                return $journal;
            } else {
                // create journal
                $journal = Journal::create([
                    'name' => $journalname
                ]);
                return $journal;
            }
        } else {
            return $journals = Journal::whereName('Chưa phân loại')->first();
        }
    }
}

?>
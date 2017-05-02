<?php

namespace App\Http\Controllers\Web;

use App\DataTables\JournalsStatisticsDataTable;
use App\DataTables\JournalStatisticsDataTable;
use App\DataTables\OrganizesStatisticsDataTable;
use App\DataTables\OrganizeStatisticsDataTable;
use App\Http\Controllers\Api\ElasticsearchApiController;
use App\Models\Journal;
use App\Models\Organize;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;

class StatisticsController extends Controller
{
    public function view()
    {
        return view('pages.statistics.statistics');
    }

    public function journals(JournalsStatisticsDataTable $dataTable)
    {

        return $dataTable->render('pages.statistics.journals');

    }

    public function organizes(OrganizesStatisticsDataTable $dataTable)
    {
        return $dataTable->render('pages.statistics.organizes');
    }

    public function journal($id, JournalStatisticsDataTable $dataTable) {
        $journal = Journal::findOrFail($id);
        return $dataTable->setJournal($journal)->render('pages.statistics.journal');
    }

    public function organize($id, OrganizeStatisticsDataTable $dataTable) {
        $organize = Organize::findOrFail($id);
        return $dataTable->setOrganize($organize)->render('pages.statistics.organize');
    }
}

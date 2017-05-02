<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Api\ElasticsearchApiController;
use App\Models\Organize;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SearchArticleFromOrganizeController extends SearchArticleController
{
    /**
     * @var Organize $organize
     */
    private $organize;

    protected function getStatistics($elasticData)
    {
        $statistics = parent::getStatistics($elasticData);

        $total = collect([
            'name' => "Tên cơ quan: " . $this->organize->name,
        ])->merge($statistics->get('total')->only('count', 'citation', 'citation_self', 'citation_scopus_isi'));

        /**
         * @var Collection $organizes
         */
        $organizes = $statistics->get('organizes');
        $organizes = $organizes->where('id', "!=", $this->organize->id);

        $statistics = $statistics->merge(compact('total', 'organizes'))->except('subjects');

        return $statistics;
    }

    protected function customContext($context)
    {
        $context['page_title'] = $this->organize->name;
        return $context;
    }

    protected function viewName()
    {
        return 'pages.search.organize_articles';
    }

    public function searchFromOrganize($id, Request $request)
    {
        /**
         * @var Organize $organize
         */
        $this->organize = $organize = Organize::findOrFail($id);
        $request->merge([
            'field' => 'organize',
            'text' => $organize->name,
        ]);
        $request->replace($request->except('advance'));
        return parent::search($request);
    }
}

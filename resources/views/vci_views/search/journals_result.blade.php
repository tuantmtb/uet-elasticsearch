<div class="portlet light">
    <div class="portlet-title">
        <div class="caption">
            {{count($journals)}} kết quả
        </div>
    </div>
    <div class="portlet-body">
        @if(count($journals) > 0)
            @foreach($journals as $journal)
                <div>
                    <h5 style="margin-bottom: 10px">
                        {{Html::link(route('search.article', ['field' => 'journal', 'text' => $journal->name]), $journal->name)}}
                    </h5>
                </div>
            @endforeach
        @else
            Không có kết quả
        @endif
    </div>
</div>
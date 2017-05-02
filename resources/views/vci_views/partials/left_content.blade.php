<div class="col-left  col-xs-12 col-md-3 col-sm-4">
    <div id="module8" class="ModuleWrapper">
        <div id="module-sidebar" class="column-search column-sidebar">
            <div class="search-filter">
                @if(isset($articles))
                    <div class="block-text">
                        <div class="block-text-content">
                            <div class="l-columns-criteria">
                                <h3 class="title4">
                                    Kết quả: <span id="hitCount">{{$articles->total()}}</span>
                                </h3>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="refine-item refine-item-open">
                    <h4 class="refine-title"><a data-toggle="collapse" href="#journal_list" aria-expanded="false"
                                                aria-controls="collapseExample" onclick="collapse($(this));">Tạp chí phổ
                            biến</a><i class="icon-arrow"></i></h4>
                    <div class="refine-content" id="journal_list">
                        <div class="refine-subitem-list">
                            @if(isset($journals ))
                                @foreach($journals as $journal)
                                    <div class="refine-subitem ">
                                        <label class="refine-subitem-title">
                                            {{-- <input type="checkbox" name="csfauthor" value="Châu Văn Minh"> --}}
                                            <a href="{{route('journal.articles', [$journal->id])}}">
                                                {{$journal->name}} ({{count($journal->articles)}})
                                            </a>
                                        </label>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="refine-item refine-item-open">
                    <h4 class="refine-title"><a data-toggle="collapse" href="#year_list" aria-expanded="false"
                                                aria-controls="collapseExample" onclick="collapse($(this));">Năm</a><i
                                class="icon-arrow"></i></h4>
                    <div class="refine-content" id="year_list">
                        <div class="refine-subitem-list">
                            @if(isset($years))
                                @foreach($years as $year)
                                    <div class="refine-subitem ">
                                        <label class="refine-subitem-title">
                                            <a href="{{route('search.article', ['field' => 'year', 'text' => $year])}}">
                                                {{$year}}
                                            </a>
                                        </label>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
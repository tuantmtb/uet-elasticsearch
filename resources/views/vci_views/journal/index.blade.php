@extends('vci_views.layouts.master')

@section('menu.journal_index', 'active')

@section('content')
    <div id="center-content" style="min-height: 450px">

        @include('vci_views.partials.search')

        <div class="row clearfix columns-widget columns3-9">
            <div class="col-right  col-xs-12 col-md-9 col-sm-8" style="float:right">
                <div id="module9" class="ModuleWrapper list_articles">
                    <div class="search-list search-result">
                        <div class="column-result">
                            <div class="searchPagination topBarSearchResult">

                                @if(Route::currentRouteName() != 'user.show')
                                    <h3 class="text-center" class="title_page_articles">
                                        @if(Route::currentRouteName() == 'journal.articles')
                                            Tạp chí {{$journal->name}}

                                        @elseif(Route::currentRouteName() == 'year.articles' )
                                            Năm {{$year}}

                                        @elseif(Route::currentRouteName() =='journal.index')
                                            Danh sách tạp chí

                                        @elseif(Route::currentRouteName() == 'search.advance.post' || Route::currentRouteName() == 'search.articles')
                                            Kết quả
                                        @else
                                            Danh sách bài báo
                                        @endif
                                    </h3>
                                    <hr>
                                @endif
                            </div>
                            <div class="result-list-item">
                                @php($stt=1)

                                @if(count($journals) > 0)
                                    @foreach($journals as $journal)
                                        {{-- {{dd($article)}} --}}
                                        {{-- Article Item --}}
                                        <div class="search-results-item">
                                            <div class="search-results-number">
                                                <div class="search-results-number-align">{{$stt++}}</div>
                                            </div>
                                            <div class="search-results-content">
                                                <div class="title">
                                                    <a href="{{route('journal.articles', [$journal->id])}}">
                                                        <h4>{{$journal->name}}</h4></a>
                                                </div>
                                                @if(Entrust::can('edit'))
                                                    <div>
                                                        <a href="{{route('manage.journal.article.non_reviewed', [$journal->id])}}">
                                                            <h5>
                                                                - Bài chưa duyệt sau
                                                                năm 2005: {{$journal->count_non_reviewed_after_2006()}}
                                                            </h5></a>
                                                    </div>
                                                    <div>
                                                        <a href="{{route('manage.journal.article.non_reviewed', [$journal->id])}}">
                                                            <h5>
                                                                - Tất cả bài chưa
                                                                duyệt: {{$journal->count_non_reviewed()}}
                                                            </h5></a>
                                                    </div>

                                                    <div>
                                                        <a href="{{route('manage.journal.article.reviewed', [$journal->id])}}">
                                                            <h5>
                                                                - Danh
                                                                sách bài đã duyệt: {{$journal->count_reviewed()}}
                                                            </h5>
                                                        </a>
                                                    </div>
                                                @endif

                                            </div>
                                            <div class="search-results-data">
                                                <div class="search-results-data-cite">
                                                    <a href="{{route('journal.articles', [$journal->id])}}"> Số bài
                                                        báo: {{count($journal->articles)}} </a><br>
                                                    <span class="en-data-bold">&nbsp;</span>
                                                </div>
                                            </div>
                                        </div>
                                        <hr/>
                                    @endforeach
                                    {{-- @if(isset($_GET['id'])) --}}
                                    <div class="searchPagination topBarSearchResult" style="margin-top: 3rem;">
                                        <div class="default-pagination paginationSearch pull-right" id="pagination9">
                                            {{ $journals->links() }}
                                        </div>
                                        {{-- <div class="clearfix"></div> --}}
                                    </div>
                                    {{-- @endif --}}
                                @else
                                    <p class="text-center">Không có dữ liệu</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            @include('vci_views.partials.left_content')
        </div>
    </div>
@endsection

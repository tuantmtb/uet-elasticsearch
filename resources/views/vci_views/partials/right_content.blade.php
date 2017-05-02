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
                        @elseif(Route::currentRouteName() == 'search.advance.post' || Route::currentRouteName() == 'search.articles')
                            Kết quả tìm kiếm
                        @elseif(Route::currentRouteName() == 'organize.show')
                            Danh sách bài báo của {{$organize->name}}
                        @elseif(Route::currentRouteName() == 'organize.statistic' )
                            Kết quả thống kê {{$organize->name}}
                        @elseif(Route::currentRouteName() == 'journal.statistics')
                            Kết quả thống kê {{$journal->name}}
                        @else
                            Danh sách bài báo
                        @endif
                    </h3>
                    <hr/>
                @endif
            </div>
            @yield('chart')
            <div class="result-list-item">
                @if(isset($articles) && count($articles) > 0)
                    @foreach($articles as $index => $article)
                        {{-- {{dd($article)}} --}}
                        {{-- Article Item --}}
                        <div class="search-results-item">
                            <div class="search-results-number">
                                <div class="search-results-number-align">
                                    {{$index + 1 + config('settings.per_page')*($articles->currentPage() - 1)}}
                                </div>
                            </div>
                            <div class="search-results-content">
                                <div class="title">
                                    <a href="{{route('show.article', [$article->id])}}"
                                       title="{{$article->titles}}">{{$article->title}}</a>
                                </div>
                                <div class="author">
                                    @php($_article = \App\Models\Article::find($article->id))
                                    @if($_article->authors->count() > 0)
                                        <span class="label-txt">Bởi: </span>
                                        @foreach($_article->authors as $index=>$author)
                                            <a style="color:#B12A23;"
                                               href="{{url('/') .'/search?text_search='. $author->name . '&cate_search=2'}}">
                                                {{$author->name}}
                                            </a>
                                            @if($index+1 < $_article->authors->count())
                                                ,
                                            @endif
                                        @endforeach
                                    @endif

                                </div>
                                <div class="year">
                                    @if(isset($article->year))
                                        <span class="label-txt">Năm: </span>
                                        <a href="{{route('year.articles', [$article->year])}}"
                                           title="{{$article->year}}">{{$article->year}}</a>
                                    @endif
                                </div>
                                <div>
                                    <div>
                                        @if(isset($article->journal_id))
                                            <span class="label-txt">Tạp chí: </span>
                                            {{--                                        @php(dd($article->journal))--}}
                                            <a style="color:#B12A23;"
                                               href="{{route('journal.articles', [$article->journal_id])}}">
                                                {{\App\Models\Journal::find($article->journal_id)->name}}
                                            </a>
                                        @endif
                                    </div>
                                    {{-- <span class="label-txt">Số tạp chí: </span>
                                    <a style="color:#B12A23;" href="/search?filters[magazineVolume]=&quot;S. 1 (2016)&quot;">S. 1 (2016)</a>
                                    <strong> - 2016</strong>  --}}
                                </div>
                                {{--@if(Auth::check())--}}
                                {{--@if(Auth::user()->can('manage'))--}}
                                {{--<div class="search-results-button" style="margin-top: 3rem">--}}
                                {{--<a href="{{route('user.article.edit', $article->id)}}"--}}
                                {{--class="btn btn-warning">--}}
                                {{--<i class="fa fa-edit"></i> Chỉnh sửa bài viết--}}
                                {{--</a>--}}
                                {{--<a href="#" class="btn btn-danger" data-toggle="modal"--}}
                                {{--data-target="#modal_delete">--}}
                                {{--<i class="fa fa-close"></i> Xóa bài viết--}}
                                {{--</a>--}}
                                {{--<!-- Modal -->--}}
                                {{--<div class="modal fade" id="modal_delete" tabindex="-1" role="dialog"--}}
                                {{--aria-labelledby="myModalLabel">--}}
                                {{--<div class="modal-dialog" role="document">--}}
                                {{--<form action="{{route('user.article.delete', [$article->id])}}"--}}
                                {{--method="POST">--}}
                                {{--<input type="hidden" name="_method" value="DELETE">--}}
                                {{--{{csrf_field()}}--}}
                                {{--<div class="modal-content">--}}
                                {{--<div class="modal-header">--}}
                                {{--<button type="button" class="close"--}}
                                {{--data-dismiss="modal"--}}
                                {{--aria-label="Close"><span--}}
                                {{--aria-hidden="true">&times;</span>--}}
                                {{--</button>--}}
                                {{--<h4 class="modal-title" id="myModalLabel">Xác nhận--}}
                                {{--xóa</h4>--}}
                                {{--</div>--}}
                                {{--<div class="modal-body">--}}
                                {{--Bạn có thực sự muốn xóa bài báo này--}}
                                {{--</div>--}}
                                {{--<div class="modal-footer">--}}
                                {{--<button type="button" class="btn btn-default"--}}
                                {{--data-dismiss="modal">Hủy bỏ--}}
                                {{--</button>--}}
                                {{--<button type="submit" class="btn btn-primary">Xác--}}
                                {{--nhận--}}
                                {{--</button>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--</form>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--@endif--}}
                                {{--@endif--}}
                            </div>
                            <div class="search-results-data">
                                <div class="search-results-data-cite">
                                    <a href="{{route('cites.article', [$article->id])}}"> Trích
                                        dẫn: {{$article->cites_count}} </a><br>
                                    <span class="en-data-bold">&nbsp;</span>
                                    <a href="{{route('show.article',[$article->id])}}"
                                       style="margin-top: 4rem;margin-left: -2rem" type="button"
                                       class="btn dark btn-outline"><i class="fa fa-book"></i> Xem chi tiết
                                    </a>
                                </div>
                            </div>
                        </div>
                        <hr/>
                    @endforeach

                    {{-- @if(isset($_GET['id'])) --}}
                    <div class="searchPagination topBarSearchResult" style="margin-top: 3rem;">
                        <div class="default-pagination paginationSearch pull-right" id="pagination9">
                            {{ $articles->links() }}
                        </div>
                        {{-- <div class="clearfix"></div> --}}
                    </div>
                    {{-- @endif --}}

                @else
                    {{--<p class="text-center">Không có dữ liệu</p>--}}
                @endif
            </div>
        </div>
    </div>
</div>

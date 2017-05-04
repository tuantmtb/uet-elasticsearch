@extends('layouts.search_result')

@section('page-title')
    Tìm kiếm phim có {{VciConstants::SEARCH_ARTICLE_FIELDS[$request->get('field')]}} là "{{$request->get('text', '')}}"
@endsection

@section('results')
    @if(count($articles) > 0)
        @foreach($articles as $index => $article)
            <li class="search-item clearfix">
                <div class="search-content">
                    <div class="row">
                        <div class="@if(!is_null($article->imdb_index)) col-sm-10 @endif col-xs-12">
                            <h2 class="search-title">
                                {{$pagingMeta['order_num']($index)}}. <a
                                        href="javascript:">{!! $article->title !!}</a>
                            </h2>
                            <p class="search-desc">
                                ID: <a href="javascript:">{{$article->movie_id}}</a>
                            </p>
                            <p class="search-desc">
                                Năm sản xuất: <a href="javascript:">{{$article->production_year}}</a>
                            </p>

                            <p class="search-desc">
                                Tóm tắt: <a href="javascript:">{!! $article->info !!}</a>
                            </p>
                        </div>
                        @if(!is_null($article->imdb_index))
                            <div class="col-sm-2 col-xs-12">
                                <p class="search-counter-number">{{$article->imdb_index}}</p>
                                <p class="search-counter-label uppercase">IMDB</p>
                            </div>
                        @endif
                    </div>
                </div>
            </li>
        @endforeach
    @else
        <li class="search-item clearfix">
            Không tìm thấy bài báo nào
        </li>
    @endif
@endsection

@section('search-hidden-fields')
    {{Form::hidden('text', $request->get('text'))}}
    {{Form::hidden('field', $request->get('field'))}}
@endsection
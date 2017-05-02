@extends('layouts.search_result')

@section('page-title')
    @if ($request->has('advance'))
        Tìm kiếm bài báo nâng cao
    @else
        Tìm kiếm bài báo có {{VciConstants::SEARCH_ARTICLE_FIELDS[$request->get('field')]}} là "{{$request->get('text', '')}}"
    @endif
@endsection

@section('results')
    @if(count($articles) > 0)
        @foreach($articles as $index => $article)
            <li class="search-item clearfix">
                <div class="search-content">
                    <div class="row">
                        <div class="col-sm-10 col-xs-12">
                            <h2 class="search-title">
                                {{$pagingMeta['order_num']($index)}}. <a
                                        href="{{route('article.show', $article->id)}}">{!! $article->title !!}</a>
                            </h2>
                            @if(count($article->authors) > 0)
                                <p class="search-desc">
                                    Tác giả: {!! VciHelper::authorsShortImplode($article) !!}
                                </p>
                            @endif
                            @if($article->journal)
                                <p class="search-desc">
                                    Tạp chí:
                                    {{Html::link(route('journal.articles', $article->journal_id), $article->journal->name, [], null, false)}}
                                    <span class="search-desc">
                                        {!! VciHelper::journalInfo($article->journal, $article->number, $article->volume, $article->year) !!}
                                    </span>
                                </p>
                            @endif
                        </div>
                        <div class="col-sm-2 col-xs-12">
                            <p class="search-counter-number">{{$article->cites_count ?: 0}}</p>
                            <p class="search-counter-label uppercase">Trích dẫn</p>
                        </div>
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
    @if($request->has('advance'))
        {{Form::hidden('advance', $request->get('advance'))}}
        @foreach($request->get('terms') as $index => $term)
            @foreach($term as $key => $value)
                {{Form::hidden("terms[$index][$key]", $value)}}
            @endforeach
        @endforeach
    @else
        {{Form::hidden('text', $request->get('text'))}}
        {{Form::hidden('field', $request->get('field'))}}
    @endif
@endsection
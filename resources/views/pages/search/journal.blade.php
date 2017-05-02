@extends('layouts.search_result')

@section('page-title')
    Tìm kiếm tạp chí có tên là "{{$request->get('text', '')}}"
@endsection

@section('results')
    @if(count($journals) > 0)
        @foreach($journals as $index => $journal)
            <li class="search-item clearfix">
                <div class="search-content">
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="search-title">
                                {{$pagingMeta['order_num']($index)}}. <a
                                        href="{{route('journal.articles',['id'=>$journal->id])}}">{{$journal->name}}</a>
                            </h2>
                        </div>
                    </div>
                </div>
            </li>
        @endforeach
    @else
        <li class="search-item clearfix">
            Không tìm thấy tạp chí nào
        </li>
    @endif
@endsection

@section('search-hidden-fields')
    {{Form::hidden('text', $request->get('text'))}}
@endsection
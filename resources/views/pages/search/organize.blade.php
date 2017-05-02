@extends('layouts.search_result')

@section('page-title')
    Tìm kiếm cơ quan có tên là "{{$request->get('text', '')}}"
@endsection

@section('results')
    @if(count($organizes) > 0)
        @foreach($organizes as $index => $organize)
            <li class="search-item clearfix">
                <div class="search-content">
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="search-title">
                                {{$pagingMeta['order_num']($index)}}. <a
                                        href="{{route('organize.articles',['id'=>$organize->id])}}">{{$organize->name}}</a>
                            </h2>
                        </div>
                    </div>
                </div>
            </li>
        @endforeach
    @else
        <li class="search-item clearfix">
            Không tìm thấy cơ quan nào
        </li>
    @endif
@endsection

@section('search-hidden-fields')
    {{Form::hidden('text', $request->get('text'))}}
@endsection
@extends('pages.search.article')

@section('title')
    {{$page_title}}
@endsection

@section('page-title')
    {{$page_title}}
@endsection

@section('search-hidden-fields')
    {!! VciHelper::requestToHidden($request, ['year', 'number', 'volume', 'must_scopus']) !!}
@endsection
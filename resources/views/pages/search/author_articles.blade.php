@extends('pages.search.article')

@section('title')
    {{$page_title}}
@endsection

@section('page-title')
    {{$page_title}}
@endsection

@section('search-hidden-fields')
@endsection

@section('page-level-scripts')
    @parent
    <script>
        var title = $("<div>" + bootbox_data.title + "</div>");
        title.find('a[href="#by_authors"]').text("Đồng tác giả");
        title.find('a[href="#by_organizes"]').text("Cơ quan hợp tác");
        bootbox_data.title = title.html();
    </script>
@endsection
@extends('layouts.page')

@section('page-level-styles')
    @parent
    {{Html::style('metronic/pages/css/search.min.css')}}
    <style>
        .search-page .search-bar input,
        .search-page .search-bar select {
            border: 1px solid #c2cad8;
            background-color: #fff;
            height: 55px;
            color: black;
        }

        .search-page .search-bar select {
            margin-top: 1px;
        }

        .search-page .search-bar input {
            font-weight: bold;
            font-size: 14pt;
            margin-top: 1px;
        }

        .search-page .search-bar input:focus,
        .search-page .search-bar select:focus,
        .search-page .search-bar input:hover,
        .search-page .search-bar select:hover {
            border-color: #007F3E;
        }

        .search-page .search-bar button {
            margin-top: 1px;
            margin-left: 0;
        }
    </style>
@endsection

@section('page-title')
    Tìm kiếm phim trong cơ sở dữ liệu của IMDB
@endsection

@section('page-body')
    <div class="portlet light">
        <div class="portlet-body">
            <div class="tab-content">
                <div class="tab-pane active" id="tab-article">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="tab-content">
                                <div class="tab-pane active in" id="tab-article-basic">
                                    <div class="search-page search-content-2">
                                        <div class="search-bar">
                                            {{Form::open(['method' => 'get', 'route' => 'search.article', 'class' => 'autotrim'])}}
                                            <div class="row">
                                                <div class="col-sm-8 col-xs-12">
                                                    <input type="text" name="text" class="form-control"
                                                           placeholder="Nội dung tìm kiếm..." autofocus required>
                                                </div>
                                                <div class="col-sm-2 col-xs-12">
                                                    {{Form::select('field', VciConstants::SEARCH_ARTICLE_FIELDS, null, ['class' => 'btn', 'style' => 'width: 100%'])}}
                                                </div>
                                                <div class="col-sm-2 col-xs-12">
                                                    <button class="btn green uppercase bold" type="submit"
                                                            style="width: 100%">
                                                        <i class="fa fa-search"></i> Tìm kiếm
                                                    </button>
                                                </div>
                                            </div>
                                            {{Form::close()}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-level-scripts')
    @parent
    <script>
        function trimAll(form) {
            $(form).find('input[type="text"]').each(function () {
                $(this).val($(this).val().trim());
            })
        }

        $('.autotrim').submit(function (e) {
            e.preventDefault();
            bootbox.dialog({
                message: '<p><i class="fa fa-spin fa-spinner"></i> Đang tìm kiếm...</p>'
            });
            trimAll(this);
            $(this).unbind('submit').submit();
        });
    </script>
@endsection
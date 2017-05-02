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
    Tìm kiếm
@endsection

@section('page-body')
    <div class="portlet light">
        <div class="portlet-title tabbable-line">
            <ul class="nav nav-tabs pull-left">
                <li class="active">
                    <a href="#tab-article" data-toggle="tab"> Bài báo </a>
                </li>
                <li class="">
                    <a href="#tab-journal" data-toggle="tab"> Tạp chí </a>
                </li>
                <li class="">
                    <a href="#tab-organize" data-toggle="tab"> Cơ quan </a>
                </li>
            </ul>
        </div>
        <div class="portlet-body">
            <div class="tab-content">
                <div class="tab-pane active" id="tab-article">
                    <div class="row">
                        <div class="col-md-2 col-sm-12 col-xs-12">
                            <ul class="nav nav-tabs tabs-left">
                                <li class="active">
                                    <a href="#tab-article-basic" data-toggle="tab"> Cơ bản </a>
                                </li>
                                <li>
                                    <a href="#tab-article-advance" data-toggle="tab"> Nâng cao </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-10 col-sm-12 col-xs-12">
                            <div class="tab-content">
                                <div class="tab-pane active in" id="tab-article-basic">
                                    <div class="search-page search-content-2">
                                        <div class="search-bar">
                                            {{Form::open(['method' => 'get', 'route' => 'search.article', 'class' => 'autotrim'])}}
                                            <div class="row">
                                                <div class="col-sm-8 col-xs-12">
                                                    <input type="text" name="text" class="form-control" placeholder="Nội dung tìm kiếm..." autofocus required>
                                                </div>
                                                <div class="col-sm-2 col-xs-12">
                                                    {{Form::select('field', VciConstants::SEARCH_ARTICLE_FIELDS, null, ['class' => 'btn', 'style' => 'width: 100%'])}}
                                                </div>
                                                <div class="col-sm-2 col-xs-12">
                                                    <button class="btn green uppercase bold" type="submit" style="width: 100%">
                                                        <i class="fa fa-search"></i> Tìm kiếm
                                                    </button>
                                                </div>
                                            </div>
                                            {{Form::close()}}
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tab-article-advance">
                                    <div class="search-page search-content-2">
                                        <div class="search-bar">
                                            {{Form::open(['method' => 'get', 'route' => 'search.article', 'class' => 'autotrim'])}}
                                            {{Form::hidden('advance', true)}}
                                            <div class="row">
                                                <input type="hidden" class="array-2d" data-dim-1="terms" data-attr="connector" value="">
                                                <div class="col-sm-6 col-sm-offset-2 col-xs-12">
                                                    <input type="text" class="form-control array-2d" data-dim-1="terms" data-attr="text" placeholder="Nội dung tìm kiếm..." autofocus required>
                                                </div>
                                                <div class="col-sm-2 col-xs-12">
                                                    {{Form::select(null, VciConstants::SEARCH_ARTICLE_FIELDS, null, ['class' => 'btn array-2d', 'style' => 'width: 100%', 'data-dim-1' => 'terms', 'data-attr' => 'field'])}}
                                                </div>
                                                <div class="col-sm-2 col-xs-12">
                                                    <button class="btn green tooltips add-term" type="button" style="width: 55px" data-original-title="Thêm">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="row margin-top-20">
                                                <div class="col-sm-2 col-xs-12 col-sm-offset-10">
                                                    <button class="btn green uppercase bold" type="submit" style="width: 100%">
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
                <div class="tab-pane" id="tab-journal">
                    <div class="search-page search-content-2">
                        <div class="search-bar">
                            <div class="row">
                                {{Form::open(['method' => 'get', 'route' => 'search.journal', 'class' => 'autotrim'])}}
                                <div class="col-sm-10 col-xs-12">
                                    <input type="text" name="text" class="form-control" placeholder="Tên tạp chí..." autofocus required >
                                </div>
                                <div class="col-sm-2 col-xs-12">
                                    <button class="btn green uppercase bold" type="submit" style="width: 100%">
                                        <i class="fa fa-search"></i> Tìm kiếm
                                    </button>
                                </div>
                                {{Form::close()}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab-organize">
                    <div class="search-page search-content-2">
                        <div class="search-bar">
                            <div class="row">
                                {{Form::open(['method' => 'get', 'route' => 'search.organize', 'class' => 'autotrim'])}}
                                <div class="col-sm-10 col-xs-12">
                                    <input type="text" name="text" class="form-control" placeholder="Tên cơ quan..." autofocus required>
                                </div>
                                <div class="col-sm-2 col-xs-12">
                                    <button class="btn green uppercase bold" type="submit" style="width: 100%">
                                        <i class="fa fa-search"></i> Tìm kiếm
                                    </button>
                                </div>
                                {{Form::close()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div hidden id="add-term-html">
        <div class="row margin-top-20" style="display: none">
            <div class="col-sm-2 col-xs-12">
                {{Form::select(null, VciConstants::SEARCH_CONNECTORS, null, ['class' => 'btn array-2d', 'style' => 'width: 100%', 'data-dim-1' => 'terms', 'data-attr' => 'connector'])}}
            </div>
            <div class="col-sm-6 col-xs-12">
                <input type="text" class="form-control array-2d" data-dim-1="terms" data-attr="text" placeholder="Nội dung tìm kiếm..." autofocus required>
            </div>
            <div class="col-sm-2 col-xs-12">
                {{Form::select(null, VciConstants::SEARCH_ARTICLE_FIELDS, null, ['class' => 'btn array-2d', 'style' => 'width: 100%', 'data-dim-1' => 'terms', 'data-attr' => 'field'])}}
            </div>
            <div class="col-sm-2 col-xs-12">
                <button class="btn green tooltips del-term" type="button" style="width: 55px" data-original-title="Xoá">
                    <i class="fa fa-minus"></i>
                </button>
                <button class="btn green tooltips add-term" type="button" style="width: 55px" data-original-title="Thêm">
                    <i class="fa fa-plus"></i>
                </button>
            </div>
        </div>
    </div>
@endsection

@section('page-level-scripts')
    @parent
    {{Html::script('js/array_multi_dimensions.js')}}
    <script>
        function trimAll(form) {
            $(form).find('input[type="text"]').each(function () {
                $(this).val($(this).val().trim());
            })
        }

        var termArray = new Array2d([
            {
                name: 'terms',
                attributes: ['connector', 'field', 'text']
            }
        ]);

        $('.autotrim').submit(function (e) {
            e.preventDefault();
            bootbox.dialog({
                message: '<p><i class="fa fa-spin fa-spinner"></i> Đang tìm kiếm...</p>'
            });
            trimAll(this);
            $(this).unbind('submit').submit();
        });

        var add_term_el = $('#add-term-html');
        var add_term_html = add_term_el.html();
        add_term_el.remove();

        function initAddTermBtn() {
            var add_term_btns = $('.add-term');
            add_term_btns.click(function() {
                $(this).parent().parent().after(add_term_html);
                $(this).parent().parent().next().slideDown("fast");
                initAddTermBtn();
                initDelTermBtn();
                termArray.update();
            });
            add_term_btns.removeClass('add-term');
            $('.tooltips').tooltip();
        }

        function initDelTermBtn() {
            var del_term_btns = $('.del-term');
            del_term_btns.click(function() {
                $(this).parent().parent().slideUp("fast", function() {
                    $(this).remove();
                    termArray.update();
                });
            });
            del_term_btns.removeClass('del-term');
        }

        initAddTermBtn();
        termArray.update();
    </script>
@endsection
@extends('vci_views.layouts.master')

@section('page-level-plugins.styles')
    @parent
    {!! Html::style('metronic/global/plugins/bootstrap-editable/bootstrap-editable/css/bootstrap-editable.css') !!}
    {!! Html::style('metronic/global/plugins/bootstrap-editable/inputs-ext/address/address.css') !!}
@endsection

@section('head-more')
    @parent
    <style>
        th, td {
            vertical-align: middle !important;
        }

        .editable-input {
            width: 500px;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="note note-info">
                <h4 class="block">Hướng dẫn</h4>
                <p>
                    Nhấp vào phần gạch chân để sửa các thông tin. <br>
                    Nhấp vào mũi tên sang trái để chuyển thông tin từ cột phải sang cột trái <br>
                    Các thay đổi chỉ được lưu khi bấm <a href="javascript:" onclick="scrollToUpdateBtn()">cập nhật</a>. <br>
                </p>
            </div>
        </div>
    </div>
    <div id="center-content">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold">{{$article->title}}</span>
                    <span class="caption-helper">Đã duyệt {{$article->num_citation_reviewed ?: 0}} lần</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Thông tin hiện có</th>
                        <th></th>
                        <th>Thông tin tìm được</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($map->except('source') as $key => $value)
                        <tr>
                            <th>{{$value}}</th>
                            <td>
                                @if(in_array($key, ['created_at', 'updated_at', 'cites_count']))
                                    @if(in_array($key, ['created_at', 'updated_at']))
                                        {{VciHelper::formatDateTime($article->attributesToArray()[$key], 'Y-m-d H:i:s')}}
                                    @else
                                        {{$article->attributesToArray()[$key]}}
                                    @endif
                                @elseif($key === 'journal')
                                    <a href="javascript:" id="{{$key}}" class="editable" data-original-title="{{$value}}" data-type="select"
                                       data-value="{{$article->journal->id}}">
                                    </a>
                                @elseif($key === 'authors')
                                    @foreach($article_authors as $index => $author)
                                        <p>
                                            <a class="tooltips remove-author" data-original-title="Xoá tác giả">
                                                <i class="fa fa-minus"></i>
                                            </a>
                                            <a href="javascript:" id="{{$key}}-{{$index}}" class="editable"
                                               data-original-title="{{$value}}" data-type="author"
                                               data-value='{!! json_encode($author) !!}'></a>
                                        </p>
                                    @endforeach
                                    <p id="add-author-before-me">
                                        <a class="tooltips" data-original-title="Thêm tác giả" id="add-author">
                                            <i class="fa fa-plus"></i>
                                        </a>
                                    </p>
                                @else
                                    <a href="javascript:" id="{{$key}}" class="editable" data-original-title="{{$value}}"
                                       @if(in_array($key, ['volume', 'number', 'year'])) data-type="number" @elseif($key === 'source') data-type="url" @endif
                                    >
                                        {{$article->attributesToArray()[$key]}}
                                    </a>
                                @endif
                            </td>
                            <td>
                                @if(in_array($key, ['title', 'source', 'volume', 'number', 'year']))
                                    @include('vci_views.review_citation.to_left_button')
                                @endif
                            </td>
                            <td>
                                {{$raw[$key]}}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <th colspan="2">Các bài trích dẫn</th>
                        <td>@include('vci_views.review_citation.to_left_cites_button')</td>
                        <td></td>
                    </tr>
                    @foreach(range(1, $max_cites_count) as $index => $stt)
                        <tr>
                            <th style="text-align: right">{{$stt}}.</th>
                            <td></td>
                            <td>@include('vci_views.review_citation.to_left_cite_button', compact('$stt'))</td>
                            <td></td>
                        </tr>
                        @foreach($map as $key => $value)
                            <tr>
                                <td style="text-align: right">{{$value}}</td>
                                <td>
                                    @if(in_array($key, ['updated_at', 'created_at']))
                                        {{$cites->get($index, [$key => ''])[$key]}}
                                    @else
                                        <a href="javascript:" id="cite-{{$index}}-{{$key}}" class="editable" data-original-title="{{$value}}"
                                           @if(in_array($key, ['volume', 'number', 'year', 'cites_count'])) data-type="number" @elseif($key === 'source') data-type="url" @endif
                                        >
                                            {{$cites->get($index, [$key => ''])[$key]}}
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    @if(!in_array($key, ['created_at', 'updated_at']))
                                        @include('vci_views.review_citation.to_left_cite_field_button', compact('$stt'))
                                    @endif
                                </td>
                                <td>
                                    {{$raw_cites->get($index, [$key => ''])[$key]}}
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                    <tr id="add-cite-before-me">
                        <th style="text-align: right">
                            <a class="tooltips" data-original-title="Thêm trích dẫn" id="add-cite">
                                <i class="fa fa-plus"></i>
                            </a>
                        </th>
                        <td colspan="3"></td>
                    </tr>
                    </tbody>
                </table>
                </div>
            </div>
            <div class="portlet-action">
                <div class="row">
                    <div class="col-md-2 col-md-offset-3">
                        <div>
                            <button class="btn btn-primary" type="button"
                               style="width: 100%" id="update">
                                Cập nhật
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2 col-md-offset-2">
                        <a href="{{route('manage.review_citation.index')}}" class="btn btn-default"
                           style="width: 100%">
                            Quay về
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-level-plugins')
    @parent
    {!! Html::script('metronic/global/plugins/bootstrap-editable/bootstrap-editable/js/bootstrap-editable.js') !!}
    {!! Html::script('metronic/global/plugins/jquery.pulsate.min.js') !!}
@endsection

@section('scripts-more')
    @parent
    <script>
        window.$author_input_tpl_api = '{{route('api.article.review_citation.author_input_tpl')}}';

        $.fn.editable.defaults.inputclass = 'form-control';
        $.fn.editable.defaults.unsavedclass = null;
        $.fn.editable.defaults.emptytext = 'Không có thông tin';

        // fix type=number
        var original = $.fn.editableutils.setCursorPosition;
        $.fn.editableutils.setCursorPosition = function() {
            try {
                original.apply(this, Array.prototype.slice.call(arguments));
            } catch (e) { /* noop */ }
        };
    </script>
    {!! Html::script('js/vci-scholar/author_input_x_editable.js') !!}
    <script>
        var $cites_count = parseInt('{{$max_cites_count}}');
        var $authors_count = parseInt('{{count($article_authors)}}');

        function handleError(ex) {
            toastr['error']('Vui lòng thử lại', 'Lỗi không xác định');
            UI('center-content').unblock();
            console.log(ex);

            //Debug mode
            // $('html').html(ex.responseText);
        }

        function initEditable() {
            $('.editable').not('[data-type="author"]').each(function(i, a) {
                a = $(a);
                var type = a.data('type');
                if (type === 'select') {
                    a.editable({
                        prepend: "",
                        source: [
                                @foreach($journals as $value => $text)
                            {value: '{{$value}}', text: '{{$text}}'},
                            @endforeach
                        ]
                    });
                } else if(type === 'number') {
                    a.editable({
                        validate: function(number) {
                            if (number < 0) {return 'Nhập số không âm'}
                        }
                    });
                } else {
                    a.editable();
                }
            });
        }

        $(document).ready(function() {
            initEditable();
            initRemoveAuthorBtn();

            $('#update').click(function() {
                UI('center-content').block();
                var data = $('.editable').editable('getValue');
                data.cites_count = $cites_count;
                data.authors_count = $authors_count;
                data._token = window.Laravel.csrfToken;
                console.log(data);
                $.ajax({
                    url: '{{route('api.article.review_citation', $article->id)}}',
                    method: 'post',
                    data: data,
                    success: function(response) {
                        toastr['success']('Đang chuyển trang, vui lòng đợi', 'Cập nhật thành công');
                        window.location = '{{route('article.show', $article->id)}}';
                        // $('html').html(response);
                    },
                    error: handleError
                })
            });

            $('.to-left').click(function() {
                var parent = $(this).parent(); // td
                var right = parent.next().text().trim();
                var left = parent.prev().children('a').first();
                left.editable('setValue', right);
            });

            $('.to-left-all').click(function() {
                var target = $(this).data('all-target');
                $('.to-left-' + target).click();
            });

            $('#add-cite').click(function() {
                UI('center-content').block();
                var target = $('#add-cite-before-me');
                $cites_count++;
                $.ajax({
                    url: '{{route('api.article.review_citation.add_cite_view')}}',
                    method: 'post',
                    data: {
                        _token: window.Laravel.csrfToken,
                        stt: $cites_count
                    },
                    success: function(response) {
                        target.before(response);
                        UI('center-content').unblock();
                        initEditable();
                    },
                    error: handleError
                });
            });

            $('#add-author').click(function() {
                UI('center-content').block();
                var target = $('#add-author-before-me');
                $.ajax({
                    method: 'post',
                    url: '{{route('api.article.review_citation.add_author_view')}}',
                    data: {
                        _token: window.Laravel.csrfToken,
                        index: $authors_count
                    },
                    success: function(response) {
                        target.before(response);
                        UI('center-content').unblock();
                        $('.editable[data-type="author"]').editable();
                        initRemoveAuthorBtn();
                    },
                    error: handleError
                });
                $authors_count++;
            })
        });

        function initRemoveAuthorBtn() {
            $('.remove-author').click(function() {
                $authors_count--;
                $(this).parent().remove();
                $('.editable[data-type="author"]').each(function(i, a) {
                    a = $(a);
                    a.editable('destroy');
                    a.attr('id', 'authors-' + i);
                    a.editable();
                });
            });
        }

        function scrollToUpdateBtn() {
            Scroll('update').toVisible();
            $('#update').parent().pulsate({
                color: "#399bc3",
                repeat: 3
            });
        }
    </script>
@endsection
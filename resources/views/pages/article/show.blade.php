@extends('layouts.page')

@section('title')
    {{$article->title}}
@endsection

@section('page-level-styles')
    @parent
    {{Html::style('css/highcharts.css')}}
@endsection

@section('page-body')
    <div class="portlet light mt-element-ribbon">
        @if(Entrust::can('edit') && !$article->isNonReviewed())
            <div class="ribbon ribbon-right {{$article->isReviewed() ? 'ribbon-color-success' : 'ribbon-color-danger'}}  uppercase">
                <div class="ribbon-sub ribbon-right"></div>
                {{$article->isReviewed() ? 'Đã duyệt' : 'Đã loại'}}
            </div>
        @endif
        <div class="portlet-title">
            <div class="caption">
                <div class="caption-subject bold">{{$article->title}}</div>
            </div>
        </div>
        <div class="portlet-body form">
            <div class="form-horizontal">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-9" style="border-right: 1px #eee solid">
                            @permission('edit')
                            <div class="form-group">
                                <div class="col-md-10 col-md-offset-2">
                                    @include('partials.article.dt_action')
                                </div>
                            </div>
                            @endpermission
                            @if(count($authors) > 0)
                                <div class="form-group">
                                    <label class="control-label col-md-2">Tác giả: </label>
                                    <div class="col-md-10">
                                        <p class="form-control-static">
                                            {!! $authors->implode('<br/>') !!}
                                        </p>
                                    </div>
                                </div>
                            @endif
                            @if($article->journal)
                                <div class="form-group">
                                    <label class="control-label col-md-2">Tạp chí: </label>
                                    <div class="col-md-10">
                                        <p class="form-control-static">
                                            {!! VciHelper::journalWithInfo($article->journal, $article->number, $article->volume, $article->year) !!}
                                        </p>
                                    </div>
                                </div>
                            @endif
                            @if($article->language)
                                <div class="form-group">
                                    <label class="control-label col-md-2">Ngôn ngữ: </label>
                                    <div class="col-md-10">
                                        <p class="form-control-static">
                                            @if(in_array($article->language, array_keys(VciConstants::LOCALIZE)))
                                                {{VciConstants::LOCALIZE[$article->language]}}
                                            @else
                                                {{$article->language}}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-3" id="links">
                            @if($article->abstract)
                                <div class="form-group">
                                    <div class="col-md-10 col-md-offset-2">
                                        <p class="form-control-static">
                                            <a href="#section-abstract" class="reference">Tóm tắt</a>
                                        </p>
                                    </div>
                                </div>
                            @endif
                            @if($article->keyword)
                                <div class="form-group">
                                    <div class="col-md-10 col-md-offset-2">
                                        <p class="form-control-static">
                                            <a href="#section-keyword" class="reference">Từ khoá</a>
                                        </p>
                                    </div>
                                </div>
                            @endif
                            @if($article->reference)
                                <div class="form-group">
                                    <div class="col-md-10 col-md-offset-2">
                                        <p class="form-control-static">
                                            <a href="#section-reference" class="reference">Tài liệu tham khảo</a>
                                        </p>
                                    </div>
                                </div>
                            @endif
                            @if(count($citations) > 0)
                                <div class="form-group">
                                    <div class="col-md-10 col-md-offset-2">
                                        <p class="form-control-static">
                                            <a href="#section-citations" class="reference">Các bài được trích dẫn đến</a>
                                        </p>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <div class="col-md-10 col-md-offset-2">
                                    <p class="form-control-static">
                                        <a id="show-statistics">
                                            <i class="fa fa-line-chart"></i> Phân tích trích dẫn
                                        </a>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-10 col-md-offset-2">
                                    <p class="form-control-static">
                                        <a href="{{$article->source}}" target="_blank">
                                            <i class="fa fa-download"></i> Đi đến bài gốc
                                        </a>
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>

                    @if($article->abstract)
                        <h3 class="form-section" id="section-abstract">Tóm tắt</h3>
                        <div class="row">
                            <div class="col-md-12">
                                {{$article->abstract}}
                            </div>
                        </div>
                    @endif

                    @if($article->keyword)
                        <h3 class="form-section" id="section-keyword">Từ khoá</h3>
                        <div class="row">
                            <div class="col-md-12">
                                {{$article->keyword}}
                            </div>
                        </div>
                    @endif

                    @if($article->reference)
                        <h3 class="form-section" id="section-reference">Tài liệu tham khảo</h3>
                        <div class="row">
                            <div class="col-md-12">
                                {!! nl2br($article->reference) !!}
                            </div>
                        </div>
                    @endif

                    @if(count($citations) > 0)
                        <h3 class="form-section" id="section-citations">Các bài trích dẫn đến</h3>
                        <div class="row">
                            <div class="col-md-12">
                                @foreach($citations as $index => $citation)
                                    <div>
                                        {{$index + 1}}.
                                        @if($citation['authors'])
                                            {{VciHelper::mapAuthorsToNames($citation["authors"])}}.
                                        @endif
                                        <a href="{{$citation["uri"]}}">{{$citation["title"]}}</a>
                                        @if($citation['journalName'])in {{$citation["journalName"]}}@endif
                                        @if($citation['journalInfo'])({{$citation["journalInfo"]}})@endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @permission('manage')
                    <h3 class="form-section">Thông tin khác</h3>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-2">Tải về: </label>
                                <div class="col-md-10">
                                    <p class="form-control-static">
                                        <a href="{{$article->uri}}" target="_blank">{{str_limit($article->uri)}}</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-2">Lấy từ: </label>
                                <div class="col-md-10">
                                    <p class="form-control-static">
                                        <a href="{{$article->source}}"
                                           target="_blank">{{str_limit($article->source)}}</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-2">Ngày tạo: </label>
                                <div class="col-md-10">
                                    <p class="form-control-static">
                                        {{$article->created_at}}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-2">Ngày cập nhật: </label>
                                <div class="col-md-10">
                                    <p class="form-control-static">
                                        {{$article->updated_at}}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-2">Người sửa: </label>
                                <div class="col-md-10">
                                    <p class="form-control-static">
                                        @if($article->editor)
                                            {{$article->editor->name}}
                                        @else
                                            Dữ liệu được lấy tự động
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endpermission
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-level-plugins.scripts')
    @parent
    {{Html::script('js/highcharts.js')}}
@endsection

@section('page-level-scripts')
    @parent
    @if($statistics != null)
        @include('partials.statistics')
    @endif
    <script>
        $('.reference').click(function (e) {
            e.preventDefault();
            var href = $(this).attr('href');
            App.scrollTo($(href));
        })
    </script>
@endsection
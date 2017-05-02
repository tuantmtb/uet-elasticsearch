@extends('vci_views.layouts.master')

@section('head-more')
    @parent
    {{Html::style('metronic/global/plugins/select2/css/select2.min.css')}}
    {{Html::style('metronic/global/plugins/select2/css/select2-bootstrap.min.css')}}
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="portlet light">
                <div class="portlet-title">
                    <h4 class="text-center">
                        <strong>
                            Sửa bài báo
                        </strong>
                    </h4>
                </div>
                <div class="portlet-body">
                    <div class="portlet box light ">
                        <div class="portlet-body form">
                            <form role="form" id="form_create_new_article"
                                  action="{{route('user.article.update',$article->id)}}"
                                  method="POST">
                                {{csrf_field()}}
                                <div class="form-body">
                                    <input type="hidden" name="id" value="{{$article->id}}">
                                    <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
                                        <label for="" class="">Tiêu đề</label>
                                        <input type="text" class="form-control" id="title" name="title" required
                                               placeholder="Tiêu đề..." minlength="5" value="{{$article->title}}">
                                        @if ($errors->has('title'))
                                            <span class="help-block">
								            <strong>{{ $errors->first('title') }}</strong>
								        </span>
                                        @endif
                                    </div>
                                    <div class="form-group  {{ $errors->has('abstract') ? ' has-error' : '' }}">
                                        <label for="" class="">Tóm tắt</label>
                                        <textarea class="form-control" id="abstract" name="abstract"
                                                  placeholder="Tóm tắt..." rows="5"
                                        >{{$article->abstract}}</textarea>
                                        @if ($errors->has('abstract'))
                                            <span class="help-block">
								            <strong>{{ $errors->first('abstract') }}</strong>
								        </span>
                                        @endif
                                    </div>
                                    <div class="form-group  {{ $errors->has('keyword') ? ' has-error' : '' }}">
                                        <label for="" class="">Từ khóa</label>
                                        <input type="text" class="form-control" id="keyword" name="keyword"
                                               placeholder="Từ khóa..." minlength="5" value="{{$article->keyword}}">
                                        @if ($errors->has('keyword'))
                                            <span class="help-block">
								            <strong>{{ $errors->first('keyword') }}</strong>
								        </span>
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-4  {{ $errors->has('volume') ? ' has-error' : '' }}">
                                            <label for="" class="">Volume</label>
                                            <input type="text" class="form-control" id="volume" name="volume"
                                                   placeholder="Volume..." value="{{$article->volume}}">
                                            @if ($errors->has('volume'))
                                                <span class="help-block">
                                			<strong>{{ $errors->first('volume') }}</strong>
                                		</span>
                                            @endif
                                        </div>
                                        <div class="form-group col-md-4 {{ $errors->has('number') ? ' has-error' : '' }}">
                                            <label for="" class="">Số</label>
                                            <input type="text" class="form-control" id="number" name="number"
                                                   placeholder="Số..." value="{{$article->number}}">
                                            @if ($errors->has('number'))
                                                <span class="help-block">
                                			<strong>{{ $errors->first('number') }}</strong>
                                		</span>
                                            @endif
                                        </div>
                                        <div class="form-group  col-md-4 {{ $errors->has('year') ? ' has-error' : '' }}">
                                            <label for="" class="">Năm</label>
                                            <input type="text" class="form-control" id="year" name="year"
                                                   placeholder="Năm..." value="{{$article->year}}"/>
                                            @if ($errors->has('year'))
                                                <span class="help-block">
                                			<strong>{{ $errors->first('year') }}</strong>
                                		</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12 {{ $errors->has('source') ? ' has-error' : '' }}">
                                            <label for="" class="">Link bài báo</label>
                                            <input type="text" class="form-control" id="source" name="source"
                                                   placeholder="Link bài báo..." value="{{$article->source}}">
                                            @if ($errors->has('source'))
                                                <span class="help-block">
                                			<strong>{{ $errors->first('source') }}</strong>
                                		</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12 {{ $errors->has('uri') ? ' has-error' : '' }}">
                                            <label for="" class="">Link PDF</label>
                                            <input type="text" class="form-control" id="uri" name="uri"
                                                   placeholder="Link PDF..." value="{{$article->uri}}">
                                            @if ($errors->has('uri'))
                                                <span class="help-block">
                                			<strong>{{ $errors->first('uri') }}</strong>
                                		</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group  col-md-8 {{ $errors->has('journal_id') ? ' has-error' : '' }}">
                                            <label for="" class="">Tên tạp chí</label>


                                            <select class="form-control journal-select" name="journal_id">
                                                @if($article->journal_id!=null && $article->journal_id !="")
                                                    <option value="{{$article->journal_id}}">
                                                        {{$article->journal->name}}
                                                    </option>
                                                @endif
                                            </select>
                                            @if ($errors->has('journal_id'))
                                                <span class="help-block">
                                			<strong>{{ $errors->first('journal_id') }}</strong>
                                		</span>
                                            @endif
                                        </div>
                                        <div class="form-group  col-md-4 {{ $errors->has('language') ? ' has-error' : '' }}">
                                            <label class="">Ngôn ngữ</label>
                                            <select class="form-control" name="language">
                                                <option value="vi" @if($article->language == "vi") selected @endif>Tiếng
                                                    Việt
                                                </option>

                                                <option value="en" @if($article->language == "en") selected @endif>Tiếng
                                                    Anh
                                                </option>
                                                <option value="cn" @if($article->language == "cn") selected @endif>Tiếng
                                                    Trung
                                                </option>
                                                <option value="fr" @if($article->language == "fr") selected @endif>Tiếng
                                                    Pháp
                                                </option>
                                                <option value="ru" @if($article->language == "ru") selected @endif>Tiếng
                                                    Nga
                                                </option>
                                                <option value="ge" @if($article->language == "ge") selected @endif>Tiếng
                                                    Đức
                                                </option>
                                                <option value="jo" @if($article->language == "jo") selected @endif>Tiếng
                                                    Nhật
                                                </option>

                                            </select>
                                            @if ($errors->has('language'))
                                                <span class="help-block">
                                			<strong>{{ $errors->first('language') }}</strong>
                                		</span>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="form-group col-md-12 add_author">
                                            <label for="" class="">Tác giả</label>

                                            <div class="form-horizontal">
                                                <div class="form-body authors-body">

                                                    @foreach($article->authors as $author)
                                                        <div class="row margin-bottom-10">
                                                            <div class="col-md-4">
                                                                <input type="hidden" name="authors_id[]"
                                                                       value="{{$author->id}}">
                                                                <input type="text" name="names[]"
                                                                       class="form-control"
                                                                       placeholder="Họ tên" value="{{$author->name}}">
                                                            </div>
                                                            <div class="col-md-3">

                                                                <input type="text" class="form-control" name="emails[]"
                                                                       placeholder="Email" value="{{$author->email}}">
                                                            </div>
                                                            <div class="col-md-3">

                                                                {{--<select class="form-control organize-select"--}}
                                                                {{--name="organizes[]">--}}
                                                                {{--@if($author->organizes!=null && $author->organizes->count() >0)--}}
                                                                {{--<option value="{{$author->organizes->first()->id}}">--}}
                                                                {{--{{$author->organizes->first()->name}}--}}
                                                                {{--</option>--}}
                                                                {{--@else--}}
                                                                {{--<option value="-1">--}}

                                                                {{--</option>--}}
                                                                {{--@endif--}}
                                                                {{--</select>--}}
                                                                @if($author->organizes!=null && $author->organizes->count() >0)
                                                                    <input type="text" class="form-control"
                                                                           name="organizes_name[]"
                                                                           value="{{$author->organizes->first()->name}}"/>
                                                                @else
                                                                    <input type="text" class="form-control"
                                                                           name="organizes_name[]"
                                                                           value=" "/>
                                                                @endif

                                                            </div>
                                                            <div class="col-md-2 pull-right">
                                                                <input type="hidden" name="create_authors[]"
                                                                       value="0">
                                                                <a href="javascript:void(0)"
                                                                   class="btn red author-remove"><i
                                                                            class="glyphicon glyphicon-remove"></i></a>
                                                            </div>
                                                        </div>
                                                    @endforeach

                                                    <div class="row margin-bottom-10">
                                                        <div class="col-md-4">
                                                            <input type="hidden" name="authors_id[]"
                                                                   value="-1">
                                                            <input type="text" name="names[]"
                                                                   class="form-control"
                                                                   placeholder="Họ tên">
                                                        </div>
                                                        <div class="col-md-3">

                                                            <input type="text" class="form-control" name="emails[]"
                                                                   placeholder="Email">
                                                        </div>
                                                        <div class="col-md-3">

                                                            {{--<select class="form-control organize-select"--}}
                                                            {{--name="organizes[]">--}}
                                                            {{--<option value="-1">--}}

                                                            {{--</option>--}}
                                                            {{--</select>--}}
                                                            <input type="text" class="form-control"
                                                                   value=" " name="organizes_name[]"
                                                                   placeholder="Cơ quan"/>
                                                        </div>
                                                        <div class="col-md-2 pull-right">
                                                            <input type="hidden" name="create_authors[]"
                                                                   value="1">
                                                            <a href="javascript:void(0)"
                                                               class="btn red author-remove"><i
                                                                        class="glyphicon glyphicon-remove"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="margin-top-10"><a href="javascript:void(0)"
                                                                          id="add_more_author">Thêm tác giả
                                                    khác</a></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12 add_reference">
                                            <label for="" class="">Tham khảo</label>
                                            <textarea class="form-control" id="reference" name="reference"
                                                      placeholder="Tham khảo..." rows="5"
                                            >{{$article->reference}}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn green">Cập nhật</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts-more')
    {{Html::script('metronic/global/plugins/select2/js/select2.full.min.js')}}
    {{Html::script('js/vci-scholar/select_common.js')}}
    {{Html::script('js/vci-scholar/select_journal.js')}}
    {{--    {{Html::script('js/vci-scholar/select_organize.js')}}--}}
    {{Html::script('js/vci-scholar/add_article.js')}}

@endsection



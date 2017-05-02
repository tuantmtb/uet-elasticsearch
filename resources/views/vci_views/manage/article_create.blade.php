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
                            Thêm bài báo mới
                        </strong>
                    </h4>
                </div>
                <div class="portlet-body">
                    <div class="portlet box light ">
                        <div class="portlet-body form">
                            <form role="form" id="form_create_new_article" action="{{route('user.article.store')}}"
                                  method="POST">
                                {{csrf_field()}}
                                <div class="form-body">
                                    <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
                                        <label for="" class="">Tiêu đề</label>
                                        <input type="text" class="form-control" id="title" name="title" required
                                               placeholder="Tiêu đề..." minlength="5">
                                        @if ($errors->has('title'))
                                            <span class="help-block">
								            <strong>{{ $errors->first('title') }}</strong>
								        </span>
                                        @endif
                                    </div>
                                    <div class="form-group  {{ $errors->has('abstract') ? ' has-error' : '' }}">
                                        <label for="" class="">Tóm tắt</label>
                                        <textarea class="form-control" id="abstract" name="abstract"
                                                  placeholder="Tóm tắt..." rows="5"></textarea>
                                        @if ($errors->has('abstract'))
                                            <span class="help-block">
								            <strong>{{ $errors->first('abstract') }}</strong>
								        </span>
                                        @endif
                                    </div>
                                    <div class="form-group  {{ $errors->has('keyword') ? ' has-error' : '' }}">
                                        <label for="" class="">Từ khóa</label>
                                        <input type="text" class="form-control" id="keyword" name="keyword"
                                               placeholder="Từ khóa..." minlength="5">
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
                                                   placeholder="Volume...">
                                            @if ($errors->has('volume'))
                                                <span class="help-block">
                                			<strong>{{ $errors->first('volume') }}</strong>
                                		</span>
                                            @endif
                                        </div>
                                        <div class="form-group col-md-4 {{ $errors->has('number') ? ' has-error' : '' }}">
                                            <label for="" class="">Số</label>
                                            <input type="text" class="form-control" id="number" name="number"
                                                   placeholder="Số...">
                                            @if ($errors->has('number'))
                                                <span class="help-block">
                                			<strong>{{ $errors->first('number') }}</strong>
                                		</span>
                                            @endif
                                        </div>
                                        <div class="form-group  col-md-4 {{ $errors->has('year') ? ' has-error' : '' }}">
                                            <label for="" class="">Năm</label>
                                            <input type="text" class="form-control" id="year" name="year"
                                                   placeholder="Năm..."/>
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
                                                   placeholder="Link bài báo...">
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
                                                   placeholder="Link PDF...">
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
                                                {{--@foreach($journals as $journal)--}}
                                                {{--<option value="{{$journal->id}}">{{$journal->name}}</option>--}}
                                                {{--@endforeach--}}
                                            </select>
                                            @if ($errors->has('journal_id'))
                                                <span class="help-block">
                                			<strong>{{ $errors->first('journal_id') }}</strong>
                                		</span>
                                            @endif
                                        </div>
                                        <div class="form-group  col-md-4 {{ $errors->has('language') ? ' has-error' : '' }}">
                                            <label for="" class="">Ngôn ngữ</label>
                                            <select class="form-control" name="language">
                                                <option value="vi">Tiếng Việt</option>
                                                <option value="en">Tiếng Anh</option>
                                                <option value="cn">Tiếng Trung</option>
                                                <option value="fr">Tiếng Pháp</option>
                                                <option value="ru">Tiếng Nga</option>
                                                <option value="jo">Tiếng Nhật</option>
                                                <option value="ge">Tiếng Đức</option>

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

                                                    <div class="row margin-bottom-10">
                                                        <div class="col-md-4">
                                                            <input type="text" name="names[]"
                                                                   class="form-control"
                                                                   placeholder="Họ tên">
                                                        </div>
                                                        <div class="col-md-3">

                                                            <input type="text" class="form-control" name="emails[]"
                                                                   placeholder="Email">
                                                        </div>
                                                        <div class="col-md-3">
                                                            {{--<input type="text" class="form-control" name="units[]"--}}
                                                            {{--placeholder="Đơn vị">--}}

                                                            {{--<select class="form-control organize-select"--}}
                                                            {{--name="organizes[]">--}}
                                                            {{--<option value="-1">--}}

                                                            {{--</option>--}}
                                                            {{--</select>--}}

                                                            <input type="text" class="form-control"
                                                                   name="organizes_name[]" value=" "/>

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
                                                      placeholder="Tham khảo..." rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn green">Tạo mới</button>
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

    {{--{{Html::script('js/vci-scholar/select_organize.js')}}--}}
    {{Html::script('js/vci-scholar/add_article.js')}}

@endsection



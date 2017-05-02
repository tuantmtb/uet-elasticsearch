@extends('layouts.manage')

@section('page-level-plugins.styles')
    @parent
    {{Html::style('metronic/global/plugins/select2/css/select2.min.css')}}
    {{Html::style('metronic/global/plugins/select2/css/select2-bootstrap.min.css')}}
@endsection

@section('page-title', 'Thêm bài báo mới')

@section('dashboard-body')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light">
                <div class="portlet-body form">
                    {{Form::open(['method' => 'post', 'route' => 'article.store', 'role' => 'form', 'id' => 'form'])}}
                        <div class="form-body">
                            <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
                                {{Form::label('title', 'Tiêu đề <span class="required" aria-required="true">*</span>', [], false)}}
                                {{Form::text('title', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Tiêu đề', 'minlength' => 5])}}
                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group  {{ $errors->has('abstract') ? ' has-error' : '' }}">
                                {{Form::label('abstract', 'Tóm tắt')}}
                                {{Form::textarea('abstract', null, ['class' => 'form-control autosizeme', 'placeholder' => 'Tóm tắt', 'rows' => 5])}}
                                @if ($errors->has('abstract'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('abstract') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group  {{ $errors->has('keyword') ? ' has-error' : '' }}">
                                {{Form::label('keyword', 'Từ khoá')}}
                                {{Form::text('keyword', null, ['class' => 'form-control', 'placeholder' => 'Từ khoá'])}}
                                @if ($errors->has('keyword'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('keyword') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4  {{ $errors->has('volume') ? ' has-error' : '' }}">
                                    {{Form::label('volume', 'Volume')}}
                                    {{Form::text('volume', null, ['class' => 'form-control', 'placeholder' => 'Volume'])}}
                                    @if ($errors->has('volume'))
                                        <span class="help-block">
                                			<strong>{{ $errors->first('volume') }}</strong>
                                		</span>
                                    @endif
                                </div>
                                <div class="form-group col-md-4 {{ $errors->has('number') ? ' has-error' : '' }}">
                                    {{Form::label('number', 'Số')}}
                                    {{Form::text('number', null, ['class' => 'form-control', 'placeholder' => 'Số'])}}
                                    @if ($errors->has('number'))
                                        <span class="help-block">
                                			<strong>{{ $errors->first('number') }}</strong>
                                		</span>
                                    @endif
                                </div>
                                <div class="form-group  col-md-4 {{ $errors->has('year') ? ' has-error' : '' }}">
                                    {{Form::label('year', 'Năm')}}
                                    {{Form::text('year', null, ['class' => 'form-control', 'placeholder' => 'Năm'])}}
                                    @if ($errors->has('year'))
                                        <span class="help-block">
                                			<strong>{{ $errors->first('year') }}</strong>
                                		</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12 {{ $errors->has('source') ? ' has-error' : '' }}">
                                    {{Form::label('source', 'Link bài báo')}}
                                    {{Form::url('source', null, ['class' => 'form-control', 'placeholder' => 'Link bài báo'])}}
                                    @if ($errors->has('source'))
                                        <span class="help-block">
                                			<strong>{{ $errors->first('source') }}</strong>
                                		</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12 {{ $errors->has('uri') ? ' has-error' : '' }}">
                                    {{Form::label('uri', 'Link PDF')}}
                                    {{Form::url('uri', null, ['class' => 'form-control', 'placeholder' => 'Link PDF'])}}
                                    @if ($errors->has('uri'))
                                        <span class="help-block">
                                			<strong>{{ $errors->first('uri') }}</strong>
                                		</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group  col-md-8 {{ $errors->has('journal_id') ? ' has-error' : '' }}">
                                    {{Form::label('journal_id', 'Tạp chí <span class="required" aria-required="true">*</span>', [], false)}}
                                    {{Form::select('journal_id', [], null, ['class' => 'form-control'])}}
                                    @if ($errors->has('journal_id'))
                                        <span class="help-block">
                                			<strong>{{ $errors->first('journal_id') }}</strong>
                                		</span>
                                    @endif
                                </div>
                                <div class="form-group  col-md-4 {{ $errors->has('language') ? ' has-error' : '' }}">
                                    {{Form::label('language', 'Ngôn ngữ')}}
                                    {{Form::select('language', VciConstants::LOCALIZE, null,  ['class' => 'form-control'])}}
                                    @if ($errors->has('language'))
                                        <span class="help-block">
                                			<strong>{{ $errors->first('language') }}</strong>
                                		</span>
                                    @endif
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="" class="">Tác giả</label>
                                    @foreach(Session::getOldInput('authors', []) as $author)
                                        <div class="row margin-top-10">
                                            <div class="col-md-4">
                                                <input type="text" value="{{$author['name']}}" class="form-control array-2d" placeholder="Họ tên" data-dim-1="authors" data-attr="name" required>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="email" value="{{$author['email']}}" class="form-control array-2d" placeholder="Email" data-dim-1="authors" data-attr="email">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" value="{{$author['organize_name']}}" class="form-control array-2d" placeholder="Cơ quan" data-dim-1="authors" data-attr="organize_name">
                                            </div>
                                            <div class="col-md-1">
                                                <button class="btn btn-sm red author-remove" type="button">
                                                    <i class="fa fa-close"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="margin-top-10" id="add-author-before-me">
                                        <button class="btn btn-sm green" onclick="addAuthor()" type="button">
                                            <i class="fa fa-plus"></i> Thêm tác giả
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="form-group col-md-12 add_reference">
                                    {{Form::label('reference', 'Tham khảo')}}
                                    {{Form::textarea('reference', null, ['class' => 'form-control autosizeme', 'placeholder' => 'Tham khảo', 'rows' => 5])}}
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            {{Form::submit('Tạo mới', ['class' => 'btn green'])}}
                        </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>

    <div hidden id="new_author_html">
        <div class="row margin-top-10" style="display: none;">
            <div class="col-md-4">
                <input type="text" placeholder="Họ tên" class="form-control array-2d" data-dim-1="authors" data-attr="name" required>
            </div>
            <div class="col-md-4">
                <input type="email" placeholder="Email" class="form-control array-2d" data-dim-1="authors" data-attr="email">
            </div>
            <div class="col-md-3">
                <input type="text" placeholder="Cơ quan" class="form-control array-2d" data-dim-1="authors" data-attr="organize_name">
            </div>
            <div class="col-md-1">
                <button class="btn btn-sm red author-remove" type="button">
                    <i class="fa fa-close"></i>
                </button>
            </div>
        </div>
    </div>
@endsection

@section('page-level-plugins.scripts')
    @parent
    {{Html::script('metronic/global/plugins/select2/js/select2.full.min.js')}}
    {{Html::script('metronic/global/plugins/autosize/autosize.min.js')}}
@endsection

@section('page-level-scripts')
    @parent
    <script>
        var $api_journal_search = '{{route('api.journal.search')}}';
    </script>
    {{Html::script('metronic/global/plugins/select2/js/i18n/vi.js')}}
    {{Html::script('js/array_multi_dimensions.js')}}
    {{Html::script('js/vci-scholar/article_create_edit.js')}}
@endsection



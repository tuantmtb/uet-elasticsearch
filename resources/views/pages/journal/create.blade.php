@extends('layouts.manage')

@section('page-title')
    Thêm mới tạp chí
@endsection

@section('dashboard-body')
    <div class="portlet light">
        <div class="portlet-body">
            {{Form::open(['method' => 'post','route'=>'journal.store', 'role' => 'form', 'id' => 'form'])}}
            <div class="form-body">
                <div class="form-group {{$errors->has('name') ? 'has-error' : ''}}">
                    {{Form::label('name', 'Tên tạp chí <span class="required" aria-required="true">*</span>', [], false)}}
                    {{Form::text('name', null, ['class' => 'form-control', 'required' => ''])}}
                    @if ($errors->has('name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group {{$errors->has('name_en') ? 'has-error' : ''}}">
                    {{Form::label('name_en', 'Tên tạp chí (Tiếng anh)')}}
                    {{Form::text('name_en', null, ['class' => 'form-control'])}}
                    @if ($errors->has('name_en'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name_en') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group {{$errors->has('website') ? 'has-error' : ''}}">
                    {{Form::label('website', 'Website')}}
                    {{Form::text('website', null, ['class' => 'form-control'])}}
                    @if ($errors->has('website'))
                        <span class="help-block">
                            <strong>{{ $errors->first('website') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group {{$errors->has('address') ? 'has-error' : ''}}">
                    {{Form::label('address', 'Địa chỉ')}}
                    {{Form::text('address', null, ['class' => 'form-control'])}}
                    @if ($errors->has('address'))
                        <span class="help-block">
                            <strong>{{ $errors->first('address') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group {{$errors->has('description') ? 'has-error' : ''}}">
                    {{Form::label('description', 'Mô tả tạp chí')}}
                    {{Form::textarea('description', null, ['class' => 'form-control'])}}
                    @if ($errors->has('description'))
                        <span class="help-block">
                            <strong>{{ $errors->first('description') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    {{Form::submit('Tạo mới', ['class' => 'btn green'])}}
                </div>

            </div>
            {{Form::close()}}
        </div>
    </div>
@endsection

@section('page-level-scripts')
    @parent
    <script>
        $('#form').submit(function () {
            bootbox.dialog({
                message: '<p><i class="fa fa-spin fa-spinner"></i> Đang xử lý...</p>'
            });
        });
    </script>
@endsection
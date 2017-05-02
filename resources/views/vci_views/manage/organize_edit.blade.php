@extends('vci_views.manage.master')

@section('vci_views.manage.title')
    Sửa cơ quan
@endsection

@section('vci_views.manage.body')
    <div class="form-body">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {{Form::model($organize, ['method' => 'post', 'role' => 'form'])}}
        <div class="form-group {{$errors->has('parent_id') ? 'has-error' : ''}}">
            {{Form::label('parent_id', 'Thuộc cơ quan')}}
            {!! Form::select('parent_id', $organizes, null, ['class' => 'form-control', 'optional' => '']) !!}
        </div>
        <div class="form-group {{$errors->has('name') ? 'has-error' : ''}}">
            {{Form::label('name', 'Tên cơ quan')}}
            {{Form::text('name', null, ['class' => 'form-control', 'required' => '', 'min' => 5, 'max' => 200])}}
        </div>
        <div class="form-group {{$errors->has('name_en') ? 'has-error' : ''}}">
            {{Form::label('name_en', 'Tên cơ quan (Tiếng anh)')}}
            {{Form::text('name_en', null, ['class' => 'form-control', 'min' => 0, 'max' => 200])}}
        </div>
        <div class="form-group {{$errors->has('address') ? 'has-error' : ''}}">
            {{Form::label('address', 'Địa chỉ')}}
            {{Form::text('address', null, ['class' => 'form-control', 'min' => 0, 'max' => 100])}}
        </div>

        <div class="form-group {{$errors->has('description') ? 'has-error' : ''}}">
            {{Form::label('description', 'Mô tả')}}
            {{Form::textarea('description', null, ['class' => 'form-control', 'min' => 0, 'max' => 5000])}}
        </div>
        <div class="form-group">
            {{Form::submit('Cập nhật', ['class' => 'btn blue'])}}
        </div>

    </div>
    {{Form::close()}}
@endsection




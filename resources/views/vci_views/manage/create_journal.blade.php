@extends('vci_views.manage.master')

@section('vci_views.manage.title')
    Thêm mới tạp chí
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
        {{Form::open(['method' => 'post','route'=>'manage.journal.store', 'role' => 'form'])}}
        <div class="form-group {{$errors->has('name') ? 'has-error' : ''}}">
            {{Form::label('name', 'Tên tạp chí')}}
            {{Form::text('name', null, ['class' => 'form-control', 'required' => '', 'min' => 5, 'max' => 200])}}
        </div>
        <div class="form-group {{$errors->has('name_en') ? 'has-error' : ''}}">
            {{Form::label('name_en', 'Tên tạp chí (Tiếng anh)')}}
            {{Form::text('name_en', null, ['class' => 'form-control', 'required' => '', 'min' => 0, 'max' => 200])}}
        </div>
        <div class="form-group {{$errors->has('website') ? 'has-error' : ''}}">
            {{Form::label('website', 'Website')}}
            {{Form::text('website', null, ['class' => 'form-control', 'required' => '', 'min' => 0, 'max' => 100])}}
        </div>
        <div class="form-group {{$errors->has('address') ? 'has-error' : ''}}">
            {{Form::label('address', 'Địa chỉ')}}
            {{Form::text('address', null, ['class' => 'form-control', 'required' => '', 'min' => 0, 'max' => 1000])}}
        </div>

        <div class="form-group {{$errors->has('description') ? 'has-error' : ''}}">
            {{Form::label('description', 'Mô tả tạp chí')}}
            {{Form::textarea('description', null, ['class' => 'form-control', 'required' => '', 'min' => 0, 'max' => 5000])}}
        </div>

        <div class="form-group">
            {{Form::submit('Tạo mới', ['class' => 'btn blue'])}}
        </div>

    </div>
    {{Form::close()}}
@endsection

@section('scripts-more')
    @parent

@endsection




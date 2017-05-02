@extends('layouts.manage_datatable')

@section('page-title')
    Danh sách tạp chí
@endsection

@section('portlet-title')
    <div class="portlet-title">
        <div class="actions">
            <a href="{!! route('journal.create') !!}" class="btn  btn-primary">
                <i class="fa fa-plus"></i> Thêm tạp chí </a>
        </div>
    </div>
@endsection


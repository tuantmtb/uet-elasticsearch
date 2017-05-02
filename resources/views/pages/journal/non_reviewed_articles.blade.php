@extends('layouts.manage_datatable')

@section('page-title', $journal->name)

@section('portlet-title')
    <div class="portlet-title">
        <div class="caption">
            <div class="caption-subject bold">
                @if(isset($year_after))
                    Danh sách bài chưa duyệt sau {{$year_after}}
                @else
                    Danh sách bài chưa duyệt
                @endif
            </div>
        </div>
    </div>
@endsection
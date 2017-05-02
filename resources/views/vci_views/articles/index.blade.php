@extends('vci_views.layouts.master')

@section('menu.article_index', 'active')

@section('content')
    <div id="center-content" style="min-height: 450px">

        @include('vci_views.partials.search')

        <div class="row clearfix columns-widget columns3-9">
            <div class="col-right  col-xs-12 col-md-9 col-sm-8" style="float:right">

                @include('vci_views.partials.right_content')

            </div>
            @include('vci_views.partials.left_content')
        </div>
    </div>
@endsection



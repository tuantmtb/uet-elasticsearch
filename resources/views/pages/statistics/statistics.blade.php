@extends('layouts.page')

@section('page-title')
    Thống kê
@endsection

@section('page-level-plugins.styles')
    @parent
    {{Html::style('metronic/pages/css/about.min.css')}}
    <style>
        .card-icon i {
            -webkit-transition: all 0.3s;
            -moz-transition: all 0.3s;
            transition: all 0.3s;
            text-decoration: none;
            border-color: #007F3E;
        }

        .card-icon i:hover {
            background-color: #007F3E!important;
            color: white !important;
        }
    </style>
@endsection

@section('page-body')
    <div class="row">
        <div class="col-md-6">
            <div class="portlet light">
                <div class="card-icon">
                    <a href="{{route('statistics.journals')}}">
                        <i class="fa fa-book font-red-sunglo theme-font"></i>
                    </a>
                </div>
                <div class="card-title">
                    <span>
                        <a href="{{route('statistics.journals')}}">Theo tạp chí</a>
                    </span>
                </div>
                <div class="card-desc"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="portlet light">
                <div class="card-icon">
                    <a href="{{route('statistics.organizes')}}">
                        <i class="fa fa-sitemap font-green-haze theme-font"></i>
                    </a>
                </div>
                <div class="card-title">
                    <span>
                        <a href="{{route('statistics.organizes')}}">Theo cơ quan</a>
                    </span>
                </div>
                <div class="card-desc"></div>
            </div>
        </div>
    </div>
@endsection
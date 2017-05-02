@extends('layouts.manage')

@section('page-title')
    Dashboard
@endsection

@section('dashboard-body')
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="dashboard-stat2 ">
                <div class="display">
                    <div class="number">
                        <h3 class="font-green-sharp">
                            <span data-counter="counterup" data-value="{{$journals_count}}">0</span>
                        </h3>
                        <small>SỐ TẠP CHÍ</small>
                    </div>
                    <div class="icon">
                        <i class="fa fa-book"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="dashboard-stat2 ">
                <div class="display">
                    <div class="number">
                        <h3 class="font-red-haze">
                            <span data-counter="counterup" data-value="{{$articles_count}}">0</span>
                        </h3>
                        <small>SỐ BÀI BÁO</small>
                    </div>
                    <div class="icon">
                        <i class="fa fa-newspaper-o"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="dashboard-stat2 ">
                <div class="display">
                    <div class="number">
                        <h3 class="font-blue-sharp">
                            <span data-counter="counterup" data-value="{{$members_count}}">0</span>
                        </h3>
                        <small>SỐ THÀNH VIÊN</small>
                    </div>
                    <div class="icon">
                        <i class="fa fa-user"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="dashboard-stat2 ">
                <div class="display">
                    <div class="number">
                        <h3 class="font-green-sharp">
                            <span data-counter="counterup" data-value="{{$articles_accepted_count}}">0</span>
                        </h3>
                        <small>SỐ BÀI ĐÃ DUYỆT</small>
                    </div>
                    <div class="icon">
                        <i class="fa fa-check"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="dashboard-stat2 ">
                <div class="display">
                    <div class="number">
                        <h3 class="font-red-haze">
                            <span data-counter="counterup" data-value="{{$articles_rejected_count}}">0</span>
                        </h3>
                        <small>SỐ BÀI ĐÃ LOẠI</small>
                    </div>
                    <div class="icon">
                        <i class="fa fa-close"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="dashboard-stat2 ">
                <div class="display">
                    <div class="number">
                        <h3 class="font-blue-sharp">
                            <span data-counter="counterup" data-value="{{$articles_non_reviewed_count}}">0</span>
                        </h3>
                        <small>SỐ BÀI CHƯA DUYỆT</small>
                    </div>
                    <div class="icon">
                        <i class="fa fa-question"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-level-plugins.scripts')
    @parent
    {{Html::script('metronic/global/plugins/counterup/jquery.waypoints.min.js')}}
    {{Html::script('metronic/global/plugins/counterup/jquery.counterup.min.js')}}
@endsection
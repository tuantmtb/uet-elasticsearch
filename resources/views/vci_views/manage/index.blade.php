@extends('vci_views.manage.master')

@section('vci_views.manage.title')
    Quản lý
@endsection

@section('menu.manage', 'active')

@section('vci_views.manage.body')

    <div class="row">
        <div class="col-lg-4 col-md-6">
            <div class="portlet light">
                <div class="card-icon">
                    <i class="icon-book-open font-red-sunglo theme-font"></i>
                </div>
                <div class="card-title">
                    <span> Số tạp chí</span>
                </div>

                <h3 class="font-green-sharp text-center">
                    <span>{{$journals_count}}</span>
                </h3>

            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="portlet light">
                <div class="card-icon">
                    <i class="icon-book-open font-green-haze theme-font"></i>
                </div>
                <div class="card-title">
                    <span> Số bài báo </span>
                </div>

                <h3 class="font-red-haze text-center">
                    <span>{{$articles_count}}</span>
                </h3>

            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="portlet light">
                <div class="card-icon">
                    <i class="icon-user font-red-sunglo theme-font"></i>
                </div>
                <div class="card-title">
                    <span> Số thành viên </span>
                </div>
                <h3 class="font-blue-sharp text-center">
                    <span>{{$members_counts}}</span>
                </h3>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-6">
            <div class="portlet light">
                <div class="card-icon">
                    <i class="icon-check font-red-sunglo theme-font"></i>
                </div>
                <div class="card-title">
                    <span> Số bài đã duyêt</span>
                </div>

                <h3 class="font-green-sharp text-center">
                    <span>{{$articles_accepted_count}}</span>
                </h3>

            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="portlet light">
                <div class="card-icon">
                    <i class="icon-close font-green-haze theme-font"></i>
                </div>
                <div class="card-title">
                    <span> Số bài bị loại</span>
                </div>

                <h3 class="font-red-haze text-center">
                    <span>{{$articles_rejected_count}}</span>
                </h3>

            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="portlet light">
                <div class="card-icon">
                    <i class="icon-question font-red-sunglo theme-font"></i>
                </div>
                <div class="card-title">
                    <span> Số bài chưa duyệt </span>
                </div>
                <h3 class="font-blue-sharp text-center">
                    <span>{{$articles_non_reviewed_count}}</span>
                </h3>
            </div>
        </div>
    </div>
@endsection
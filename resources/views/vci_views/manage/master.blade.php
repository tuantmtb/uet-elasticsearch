@extends('vci_views.layouts.master')

@section('content')
    <div id="" style="min-height: 450px; ">
        <div class="row">
            <div class="col-md-4">
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-cog font-dark"></i>
                            <span class="caption-subject font-dark bold ">Quản lý</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="links-item">
                            @role('admin')
                            <h4>
                                <i class="fa fa-circle-o"></i>
                                <a href="{{route('manage.backup')}}"> DB Backup </a>
                            </h4>
                            @endrole
                            <h4>
                                <i class="fa fa-circle-o"></i>
                                <a href="{{route('journal.index')}}"> Quản lý tạp chí </a>
                            </h4>

                            <h4><i class="fa fa-circle-o"></i> Quản lý bài báo</h4>
                            <h5><a href="{{route('article.create')}}">Thêm bài mới</a></h5>
                            <h5><a href="{{route('article.non_reviewed')}}">Các bài chưa duyệt</a></h5>
                            <h5><a href="{{route('article.reviewed')}}">Các bài đã được duyệt</a></h5>
                            <h5>{!! Html::linkRoute('manage.editor_statistics', 'Thống kê người chỉnh sửa') !!}</h5>
                            <h4><i class="fa fa-circle-o"></i> Quản lý cơ quan</h4>
                            <h5><a href="{{route('manage.organizes.tree')}}">Danh sách cơ quan</a></h5>
                            <h5><a href="{{route('manage.organize.new')}}">Thêm cơ quan</a></h5>
                            <h4><i class="fa fa-circle-o"></i> Quản lý tạp chí</h4>
                            <h5><a href="{{route('journal.index')}}">Danh sách tạp chí</a></h5>
                            <h4>
                                <i class="fa fa-circle-o"></i>
                                <a href="{{route('manage.review_citation.index')}}">Xét duyệt citation</a>
                            </h4>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            @yield('vci_views.manage.title')
                        </div>

                    </div>
                    <div class="portlet-body">
                        @yield('vci_views.manage.body')
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
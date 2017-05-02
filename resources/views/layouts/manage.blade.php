@extends('layouts.page')

@section('theme-layout-styles')
    @parent
    {{Html::style('css/page-sidebar.css')}}
@endsection

@section('page-body')
    <div class="row">
        <div class="col-md-3 col-sm-12 animation-all" id="left-col">
            <div class="page-sidebar-wrapper" >
                <!-- BEGIN SIDEBAR -->
                <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                <div class="page-sidebar navbar-collapse" data-auto-scroll="false" style=" width: 100%">
                    <!-- BEGIN SIDEBAR MENU -->
                    <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
                    <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
                    <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
                    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                    <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
                    <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                    <ul class="page-sidebar-menu page-header-fixed " data-keep-expanded="true" data-auto-scroll="false" data-slide-speed="200" style="border: 1px solid #eee">
                        <li class="nav-item sidebar-toggler-wrapper ">
                            <a href="javascript:" style="text-align: center" class="sidebar-toggler tooltips" data-original-title="Ẩn/hiện">
                                <i class="fa fa-bars"></i>
                            </a>
                        </li>

                        @role('admin')
                        <li class="nav-item @if(Route::currentRouteNamed('manage.backup')) active @endif">
                            <a href="{{route('manage.backup')}}" class="nav-link">
                                <i class="fa fa-database"></i>
                                <span class="title">DB Backup</span>
                            </a>
                        </li>
                        @endrole
                        <li class="nav-item @if(Route::currentRouteNamed('manage.editor_statistics')) active @endif">
                            <a href="{{route('manage.editor_statistics')}}" class="nav-link">
                                <i class="fa fa-user"></i>
                                <span class="title">Thống kê biên tập viên</span>
                            </a>
                        </li>
                        <li class="nav-item open @if(VciHelper::currentRouteNameIn(['journal.create', 'journal.index', 'journal.statistics'])) active @endif">
                            <a href="javascript:" class="nav-link nav-toggle">
                                <i class="fa fa-book"></i>
                                <span class="title">Quản lý tạp chí</span>
                                <span class="arrow open"></span>
                            </a>
                            <ul class="sub-menu" style="display: block">
                                <li class="nav-item @if(Route::currentRouteNamed('journal.create')) active @endif">
                                    <a href="{{route('journal.create')}}" class="nav-link ">
                                        <span class="title">Thêm tạp chí</span>
                                    </a>
                                </li>
                                <li class="nav-item @if(Route::currentRouteNamed('journal.index')) active @endif">
                                    <a href="{{route('journal.index')}}" class="nav-link ">
                                        <span class="title">Danh sách tạp chí</span>
                                    </a>
                                </li>
                                <li class="nav-item @if(Route::currentRouteNamed('journal.statistics')) active @endif">
                                    <a href="{{route('journal.statistics')}}" class="nav-link ">
                                        <span class="title">Thống kê tạp chí</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item open @if(VciHelper::currentRouteNameIn(['article.create', 'article.reviewed', 'article.non_reviewed', 'manage.review_citation.index'])) active @endif">
                            <a href="javascript:" class="nav-link nav-toggle">
                                <i class="fa fa-newspaper-o"></i>
                                <span class="title">Quản lý bài báo</span>
                                <span class="arrow open"></span>
                            </a>
                            <ul class="sub-menu" style="display: block">
                                <li class="nav-item  @if(Route::currentRouteNamed('article.create')) active @endif">
                                    <a href="{{route('article.create')}}" class="nav-link ">
                                        <span class="title">Thêm bài báo</span>
                                    </a>
                                </li>
                                <li class="nav-item  @if(Route::currentRouteNamed('article.reviewed')) active @endif">
                                    <a href="{{route('article.reviewed')}}" class="nav-link ">
                                        <span class="title">Danh sách đã duyệt</span>
                                    </a>
                                </li>
                                <li class="nav-item  @if(Route::currentRouteNamed('article.non_reviewed')) active @endif">
                                    <a href="{{route('article.non_reviewed')}}" class="nav-link ">
                                        <span class="title">Danh sách chưa duyệt</span>
                                    </a>
                                </li>
                                <li class="nav-item  @if(Route::currentRouteNamed('manage.review_citation.index')) active @endif">
                                    <a href="{{route('manage.review_citation.index')}}" class="nav-link ">
                                        <span class="title">Duyệt trích dẫn</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item open @if(VciHelper::currentRouteNameIn(['manage.organize.new', 'manage.organizes.tree'])) active @endif">
                            <a href="javascript:" class="nav-link nav-toggle">
                                <i class="fa fa-sitemap"></i>
                                <span class="title">Quản lý cơ quan</span>
                                <span class="arrow open"></span>
                            </a>
                            <ul class="sub-menu" style="display: block">
                                <li class="nav-item  @if(Route::currentRouteNamed('manage.organize.new')) active @endif">
                                    <a href="{{route('manage.organize.new')}}" class="nav-link ">
                                        <span class="title">Thêm cơ quan</span>
                                    </a>
                                </li>
                                <li class="nav-item  @if(Route::currentRouteNamed('manage.organize.tree')) active @endif">
                                    <a href="{{route('manage.organizes.tree')}}" class="nav-link ">
                                        <span class="title">Danh sách cơ quan</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <!-- END SIDEBAR MENU -->
                    <!-- END SIDEBAR MENU -->
                </div>
                <!-- END SIDEBAR -->
            </div>
            <!-- END SIDEBAR -->
        </div>
        <div class="col-md-9 col-sm-12 animation-all" id="right-col">
            @yield('dashboard-body')
        </div>
    </div>
@endsection

@section('theme-layout-scripts')
    @parent
    {{Html::script('js/page-sidebar.js')}}
@endsection
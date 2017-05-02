@extends('layouts.base')

@section('body')
    <header>
        <div class="page-header">
            <div class="banner">
                <a href="{{route('home')}}">
                    {{Html::image('img/banner.png', null, ['style' => 'width: 100%'])}}
                </a>

            </div>
            <div class="page-header-top">
                <div class="container-fluid">
                    <div class="page-logo"></div>
                    <a href="javascript:" class="menu-toggler"></a>
                    @include('partials.top-menu')
                </div>
            </div>
            <div class="page-header-menu">
                <div class="container">
                    @include('partials.top-menu')
                    <div class="hor-menu  ">
                        <ul class="nav navbar-nav">
                            <li class="@if(Route::currentRouteNamed('home')) active @endif tooltips" data-original-title="Home">
                                <a href="{{route('home')}}"> Trang chủ </a>
                            </li>
                            <li class="@if(VciHelper::currentRouteNameIn(['search', 'search.article', 'search.journal'])) active @endif tooltips" data-original-title="Journals of Vietnam">
                                <a href="{{route('search')}}"> Tìm kiếm </a>
                            </li>
                            <li class="@if(VciHelper::currentRouteNameIn(['statistics.journals', 'statistics.journal'])) active @endif tooltips" data-original-title="Journal Ranking">
                                <a href="{{route('statistics.journals')}}">Xếp hạng tạp chí</a>
                            </li>
                            <li class="tooltips" data-original-title="Research Ranking">
                                <a href="javascript:">Xếp hạng nghiên cứu</a>
                            </li>
                            <li class="tooltips" data-original-title="Vietnam Database">
                                <a href="javascript:">Tư liệu Việt Nam</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main>
        <div class="page-container">
            <div class="page-content-wrapper">
                @yield('page')
            </div>
        </div>
    </main>
    <footer>
        <div class="page-footer">
            <div class="container"> © 2016 Vietnam National University, Hanoi.</div>
        </div>
        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
    </footer>
@endsection

@section('page-level-scripts')
    @parent
    <script>
        function logout() {
            $('#logout-form').submit();
        }
    </script>
@endsection
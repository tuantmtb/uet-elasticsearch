@extends('layouts.base')

@section('body')
    <header>
        <div class="page-header">
            <div class="page-header-top">
                <div class="container-fluid">
                    <div class="page-logo"></div>
                    <a href="javascript:" class="menu-toggler"></a>
                </div>
            </div>
            <div class="page-header-menu">
                <div class="container">
                    <div class="hor-menu  ">
                        <ul class="nav navbar-nav">
                            <li class="@if(VciHelper::currentRouteNameIn(['search', 'search.article', 'search.journal'])) active @endif tooltips" data-original-title="Journals of Vietnam">
                                <a href="{{route('search')}}"> Tìm kiếm </a>
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
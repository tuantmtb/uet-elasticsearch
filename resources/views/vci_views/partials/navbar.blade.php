<div id="module5" class="ModuleWrapper" modulerootid="1469090">
    <nav id="menu5" class="main-menu navigation-menu-type-21">
        <div class="navbar navbar-default relative ">
            <div class="container-fluid">
                <div class="flex-flow-rw justify-content-fs  align-items-fs">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                data-target="#bs-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="http://vci.vnu.edu.vn/" title="Vietnam Citation Index">

                        </a>
                    </div>
                    <div id="bs-navbar-collapse-1" class="collapse navbar-collapse">
                        <div class="">
                            <ul class="nav navbar-nav" data-orientation="horizontal">
                                <li class="item-block @yield('menu.home')">
                                    <a href="{{route('home')}}">
                                        <span>Trang chủ</span>
                                    </a>
                                </li>
                                <li class="item-block @yield('menu.search_article')">
                                    <a href="{{route('search.article')}}">
                                        <span>Tìm kiếm</span>
                                    </a>
                                </li>
                                <li class="item-block @yield('menu.statistics')">
                                    <a href="{{route('statistics')}}">
                                        <span>Thống kê</span>
                                    </a>
                                </li>
                                @if(Entrust::can('edit'))
                                    <li class="item-block @yield('menu.journal_index')">
                                        <a href="{{route('journal.index')}}">
                                            <span>Danh sách tạp chí</span>
                                        </a>
                                    </li>
                                @endif

                                @if(Entrust::can('edit'))
                                    <li class="item-block advance_search @yield('menu.manage')">
                                        <a href="{{ route('manage.dashboard')}}">
                                            <span>Quản lý</span>
                                        </a>
                                    </li>
                                @endif


                            </ul>
                        </div>
                        <ul class="settuser list-login">
                            @if(Auth::check())
                                <li class="useritem">
                                    <a class="text-color-2" href="javascript:">
                                        {{Auth::user()->name}}
                                    </a>
                                </li>
                                <li>|</li>
                                <li class="useritem">
                                    <a href="{{ url('/logout') }}"
                                       onclick="event.preventDefault();
									document.getElementById('logout-form').submit();">Thoát</a>
                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST"
                                          style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            @else
                                <li class="useritem"><a href="{{route('login')}}">Đăng nhập</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</div>
<div style="height: 10px"></div>
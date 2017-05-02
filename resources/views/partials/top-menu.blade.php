<div class="top-menu">
    <ul class="nav navbar-nav pull-right">
        @if(Auth::check())
            <li class="dropdown dropdown-user ">
                <a href="javascript:" class="dropdown-toggle" data-toggle="dropdown"
                   data-hover="dropdown" data-close-others="true">
                    <span class="username">
                        {{Auth::user()->name}}
                        <i class="fa fa-angle-down"></i>
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-default">
                    @permission('edit')
                    <li>
                        <a href="{{route('manage.dashboard')}}">
                            <i class="fa fa-tachometer"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{route('statistics')}}">
                            <i class="fa fa-line-chart"></i> Thống kê
                        </a>
                    </li>
                    @endpermission
                    <li class="divider"></li>
                    <li>
                        <a href="javascript:" onclick="logout()">
                            <i class="fa fa-sign-out"></i> Đăng xuất
                        </a>
                        {{Form::open(['method' => 'post', 'route' => 'logout', 'id' => 'logout-form'])}}
                        {{Form::close()}}
                    </li>
                </ul>
            </li>
        @else
            <li>
                <a href="{{route('login')}}">Đăng nhập</a>
            </li>
        @endif
    </ul>
</div>
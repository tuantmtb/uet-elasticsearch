<!DOCTYPE html>
<html lang="en">
@include('vci_views.partials.head')
<body class="page-header page-content-white page-full-width page-header-menu" id="app">
{{-- <div id="app">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    &nbsp;
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ url('/logout') }}"
                                        onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')
</div> --}}

<div id="whole-page" class="container">
    <div id="page-header">
        {{-- Header --}}
        <div id="module4" class="ModuleWrapper">
            @include('vci_views.partials.header')
        </div>
        {{-- End header --}}
        @if(strpos(url()->current(), 'auth') == false)
            @include('vci_views.partials.navbar')
        @endif
    </div>
    <div id="page-content">
        @yield('content')
        {{-- Footer --}}
        <div class="clearfix" style="clear:both"></div>
        <div id="page-footer">
            @include('vci_views.partials.footer')
        </div>
        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
        {{-- End footer --}}
    </div>
</div>
<!-- Scripts -->

{{--{{Html::script('js/app.js')}}--}}
@include('vci_views.partials.footerscript')
{{--{{Html::script('js/vci-scholar/searchauthor.js')}}--}}
<script>
    //    $('.drop_ids').select2({tags: true})
    //    // $(document).ready(function(){
    //    var url = window.location.href;
    //    if (url.indexOf('user') > 0) {
    //        $('.advance_search').addClass('active');
    //        $('.list_articles_link').removeClass('active');
    //    } else {
    //        $('.list_articles_link').addClass('active');
    //        $('.advance_search').removeClass('active');
    //    }

    // })


</script>
</body>
</html>
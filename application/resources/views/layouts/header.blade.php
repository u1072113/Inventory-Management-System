<header class="main-header">
    <!-- Logo -->
    <a class="logo" href="{{url('/')}}">{!!env('COMPANY_NAME')!!}</a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav role="navigation" class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a role="button" data-toggle="offcanvas" class="sidebar-toggle" href="#">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                @include('layouts/partials/email')
                        <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <img alt="User Image" class="user-image" src="{{App\Helper::avatar()}}">
                        <span class="hidden-xs">{{Auth::user()->name}}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img alt="User Image" class="img-circle" src="{{App\Helper::avatar()}}">

                            <p>
                                {{Auth::user()->name}} - {{Auth::user()->jobTitle}}
                                <small>{!!Helper::translateAndShorten('Member since','sidebar',200)!!} {{Auth::user()->created_at->format('Y-m-d')}}</small>
                            </p>
                        </li>

                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a class="btn bg-red  btn-flat"
                                   href="{{url('user/'.Auth::user()->id.'/edit')}}"> {!! Helper::translateAndShorten('Reset Password','sidebar',50)!!}</a>
                            </div>
                            <div class="pull-right">
                                <a class="btn bg-purple  btn-flat" href="{{url('auth/logout')}}"> {!! Helper::translateAndShorten('Sign out','sidebar',10)!!}</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
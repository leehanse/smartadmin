<div class="navbar navbar-inverse navbar-fixed-top">
        <!-- start: TOP NAVIGATION CONTAINER -->
        <div class="container">
                <div class="navbar-header">
                        <!-- start: RESPONSIVE MENU TOGGLER -->
                        <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
                                <span class="clip-list-2"></span>
                        </button>
                        <!-- end: RESPONSIVE MENU TOGGLER -->
                        <!-- start: LOGO -->
                        <a class="navbar-brand" href="{{ URL::route('indexDashboard') }}">
                                CRCARD
                        </a>
                        <!-- end: LOGO -->
                </div>
                <div class="navbar-tools">
                        <!-- start: TOP NAVIGATION MENU -->
                        <ul class="nav navbar-right">                                
                                <!-- start: USER DROPDOWN -->
                                <li class="dropdown current-user">
                                        <a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" data-close-others="true" href="#">
                                                @if(Sentry::getUser()->image)
                                                    <img class="circle-img" src="{{URL::route('image',array('module' => 'user','src'=> Sentry::getUser()->image, 'size'=> '30x30'))}}" alt="">
                                                @else
                                                    <img class="circle-img" src="{{URL::route('image',array('module' => 'user','src'=> 'default.png', 'size'=> '30x30'))}}" alt="">
                                                @endif
                                                
                                                @if(Sentry::check())
                                                <span class="username">{{ Sentry::getUser()->username }}</span>
                                                @endif
                                                <i class="clip-chevron-down"></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                                <li>
                                                        <a href="{{URL::route('getProfile')}}">
                                                                <i class="clip-user-2"></i>
                                                                &nbsp;My Profile
                                                        </a>
                                                </li>
                                                <li class="divider"></li>
                                                <!--
                                                <li>
                                                        <a href="utility_lock_screen.html"><i class="clip-locked"></i>
                                                                &nbsp;Lock Screen </a>
                                                </li>
                                                -->
                                                <li>
                                                        <a href="{{ URL::route('logout') }}">
                                                                <i class="clip-exit"></i>
                                                                &nbsp;Log Out
                                                        </a>
                                                </li>
                                        </ul>
                                </li>
                                <!-- end: USER DROPDOWN -->
                        </ul>
                        <!-- end: TOP NAVIGATION MENU -->
                </div>
        </div>
        <!-- end: TOP NAVIGATION CONTAINER -->
</div>
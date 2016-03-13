<header class="main-header">
    @if(isset($blur))
        <div class="blurry-container">
            <div class="black-overlay"></div>
            <div class="blurry-background">
                <div class="background-image"></div>
            </div>
        </div>
    @endif
    <nav class="navbar navbar-default" id="main-nav">
        <div class="container">
            <div class="navbar-header">
                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#spark-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <div class="logo">
                    <a class="navbar-brand animated infinite pulse" href="{{ url('/') }}" id="brand-link">
                        <img src="{{ url('favicon.png') }}" alt="Abletive教学视频站" style="height: 100%;">
                    </a>
                </div>
            </div>

            <div class="collapse navbar-collapse" id="spark-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('/') }}">{{ trans('app/site.title') }}</a></li>
                </ul>
                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right animated slideInDown">
                    <!-- Authentication Links -->
                    <li class="animated fadeIn"><a href="javascript:;" id="search-btn"><i class="fa fa-search"></i></a></li>
                    <li class="animated fadeIn dropdown">
                        <a href="#">{{ trans("header/navbar.library") }}&nbsp;<i class="fa fa-btn fa-angle-double-down"></i></a>

                        <ul class="dropdown-menu animated fadeInRight" role="menu">
                            <li><a href="{{ url('series') }}"><i class="fa fa-btn fa-puzzle-piece"></i> {{ trans('header/navbar.library_items.series') }}</a></li>
                            <li><a href="{{ url('lessons') }}"><i class="fa fa-btn fa-tasks"></i> {{ trans('header/navbar.library_items.catalog') }}</a></li>
                            <li><a href="{{ url('tags') }}"><i class="fa fa-btn fa-tag"></i> {{ trans('header/navbar.library_items.tags') }}</a></li>
                            <li><a href="https://appsto.re/cn/6r8M-.i" target="_blank"><i class="fa fa-btn fa-apple"></i> iOS App</a></li>
                            <li><a href="http://app.abletive.com/tvos" target="_blank"><i class="fa fa-btn fa-television"></i> tvOS App</a></li>
                        </ul>
                    </li>
                    <li class="animated fadeIn dropdown">
                        <a href="#">{{ trans("header/navbar.skills") }}&nbsp;<i class="fa fa-btn fa-angle-double-down"></i></a>

                        <ul class="dropdown-menu animated fadeInRight" role="menu">
                            <li>
                                <a href="{{ url('skills/live') }}">
                                    <svg class="ableton-logo" version="1.1" id="ableton-logo"
                                         xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                         x="0px" y="0px" width="23px" height="14px" viewBox="0 0 45 21"
                                         enable-background="new 0 0 45 21" xml:space="preserve">
                                        <g>
                                            <rect width="3" height="21"></rect>
                                            <rect x="6" width="3" height="21"></rect>
                                            <rect x="12" width="3" height="21"></rect>
                                            <rect x="18" width="3" height="21"></rect>
                                            <g>
                                                <rect x="24" y="18" width="21" height="3"></rect>
                                                <rect x="24" y="12" width="21" height="3"></rect>
                                                <rect x="24" y="6" width="21" height="3"></rect>
                                                <rect x="24" width="21" height="3"></rect>
                                            </g>
                                        </g>
                                    </svg>
                                    Ableton Live
                                </a>
                            </li>
                            <li><a href="{{ url('skills/launchpad') }}"><i class="fa fa-btn fa-th"></i>Launchpad</a></li>
                            <li><a href="{{ url('skills/produce') }}"><i class="fa fa-btn fa-music"></i> {{ trans('header/navbar.skill_items.music_production') }}</a></li>
                            <li><a href="{{ url('skills/dj') }}"><i class="fa fa-btn fa-headphones"></i> DJ</a></li>
                            <li><a href="{{ url('skills/controller') }}"><i class="fa fa-btn fa-gamepad"></i> {{ trans('header/navbar.skill_items.other') }}</a></li>
                        </ul>
                    </li>
                    <li class="animated fadeIn dropdown">
                        <a href="#">{{ trans("header/navbar.account") }}&nbsp;<i class="fa fa-btn fa-angle-double-down"></i></a>

                        <ul class="dropdown-menu animated fadeInRight" role="menu">
                            <li><a href="{{ url('language/') . '/' }}{{ app()->getLocale() == "zh" ? "en" : "zh" }}"><i class="fa fa-btn fa-language"></i> {{ trans('app/site.menu.language') }}</a></li>
                            @if (auth()->guest())
                                <li><a href="javascript:;" id="login-btn"><i class="fa fa-btn fa-plug"></i> {{ trans('header/navbar.account_items.login') }}</a></li>
                                <li><a href="{{ url('register') }}"><i class="fa fa-btn fa-plus"></i> {{ trans('header/navbar.account_items.register') }}</a></li>
                            @else
                                <li><a href="{{ auth()->user()->profileLink() }}"><i class="fa fa-btn fa-street-view"></i> {{ str_limit(auth()->user()->display_name, 12) }}</a></li>
                                <li class="divider"></li>
                                @if(auth()->user()->isManager())
                                <li><a href="{{ url('manage') }}"><i class="fa fa-btn fa-cogs"></i> {{ trans('header/navbar.account_items.manage') }}</a></li>
                                <li><a href="{{ url('publish/lessons') }}"><i class="fa fa-btn fa-list-alt"></i> {{ trans('header/navbar.account_items.publish') }}</a></li>
                                <li class="divider"></li>
                                @elseif(auth()->user()->isTutor())
                                <li><a href="{{ url('publish/lessons') }}"><i class="fa fa-btn fa-list-alt"></i> {{ trans('header/navbar.account_items.publish') }}</a></li>
                                <li class="divider"></li>
                                @endif
                                <li><a href="{{ url('settings') }}"><i class="fa fa-btn fa-sliders"></i> {{ trans('header/navbar.account_items.settings') }}</a></li>
                                <li><a href="{{ url('profile') }}"><i class="fa fa-btn fa-dashboard"></i> {{ trans('header/navbar.account_items.profile') }}</a></li>
                                <li><a href="{{ url('laters') }}"><i class="fa fa-btn fa-clock-o"></i> {{ trans('header/navbar.account_items.watch_laters') }}</a></li>
                                <li><a href="{{ url('favorites') }}"><i class="fa fa-btn fa-heart"></i> {{ trans('header/navbar.account_items.favorites') }}</a></li>
                                <li><a href="{{ url('history') }}"><i class="fa fa-btn fa-calendar-check-o"></i> {{ trans('header/navbar.account_items.history') }}</a></li>
                                <li class="divider"></li>
                                <li><a href="javascript:;" id="logout-btn"><i class="fa fa-btn fa-power-off"></i> {{ trans('header/navbar.account_items.logout') }}</a></li>
                            @endif
                                <li class="divider"></li>
                                <li><a href="http://abletive.com"><i class="fa fa-btn fa-fort-awesome"></i> {{ trans('header/navbar.account_items.back_to_abletive') }}</a></li>
                        </ul>
                    </li>
                    @unless(auth()->guest())
                        <li class="animated rotateIn">
                            <img class="img-circle avatar" src="{{ auth()->user()->avatar }}" alt="">
                        </li>
                    @endunless
                </ul>
                @unless(auth()->guest())
                <!-- Experience -->
                <div class="experience animated slideInRight">
                    <span class="xp">XP: </span>
                    <span id="user-experience" title="{{ trans('header/navbar.experience') }}">{{ auth()->user()->experience }}</span>
                </div>
                @endunless
            </div>
        </div>
    </nav>
    @yield('header-content')
    @yield('banner')
</header>
@include('layouts.partials.flash')
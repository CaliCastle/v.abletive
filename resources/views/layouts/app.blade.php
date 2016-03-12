<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ config('app.site.description') }}">
    <meta name="keywords" content="{{ config('app.site.keywords') }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Cali Castle">
    <meta property="og:title" content="{{ config('app.site.title') }}">
    <meta property="og:url" content="{{ config('app.site.url') }}">
    <meta name="apple-itunes-app" content="app-id=1050395770" />

    <link rel="icon" href="{{ url('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ url('favicon.png') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ url('favicon.png') }}">

    <title>@yield('title') {{ config('app.site.separator') . trans('app/site.title') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="/css/lato.css">

    <!-- Styles -->
    <link href="{{ elixir('assets/styles.css') }}" rel="stylesheet">
    @stack('styles')

    <script src="{{ url('js/modernizr.custom.js') }}"></script>

    <!--[if IE]>
    <script async src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    @stack('scripts.header')
</head>
<body id="app-layout">
    <div class="search-box">
        <input type="search" id="search-box" name="keyword" placeholder="{{ trans('messages.search_placeholder') }}..." autocomplete="off">
        <div class="search-result-wrap">
            <ul class="search-results"></ul>
        </div>
    </div>
    <div class="search-overlay"></div>

    <div id="root" class="page">
        @include('layouts.partials.nav')

        @include('layouts.partials.menu')

        <div class="content-wrap">
            @yield('content')
        </div>

        @include('layouts.partials.footer')

        @include('layouts.partials.dialogs.login')
    </div>

    <!-- JavaScripts -->
    <script>
        var searchURL = "{{ url('search') }}/",
            $_token = "{{ csrf_token() }}";
    </script>
    <script src="{{ elixir('assets/scripts.js') }}"></script>
    <script>
        $(function () {
            'use strict';

//            $('img').each(function () {
//                if ($(this).attr('src').indexOf("https") <= 0) {
//                    $(this).attr('src', $(this).attr('src').replace("http", "https"));
//                }
//            });

            @if(Auth::check())
            function logoutDidClick() {
                swal({
                    title: "{{ trans('app/alert.logout.heading') }}",
                    text: "{{ trans('app/alert.logout.message') }}",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: "{{ trans('app/alert.cancel') }}",
                    confirmButtonText: "{{ trans('app/alert.logout.confirm') }}",
                    closeOnConfirm: false
                }, function(){
                    window.location.href = "{{ url('/logout') }}";
                });
            }

            $('a#logout-btn').each(function () {
                $(this).click(function () {
                    logoutDidClick();
                });
            });
        @else
            function loginDidClick() {
                var dialog = document.getElementById("login-dialog"),
                        dlg = new DialogFx( dialog );
                dlg.toggle();
            }

            $('a#login-btn').each(function () {
                $(this).click(function () {
                    loginDidClick();
                });
            });
        @endif
        });

    </script>
    <script async type="text/javascript" src="https://tajs.qq.com/stats?sId=54601062" charset="UTF-8"></script>
    @stack('scripts.footer')
</body>
</html>

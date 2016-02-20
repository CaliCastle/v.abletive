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

    <link rel="icon" href="{{ url('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ url('favicon.png') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ url('favicon.png') }}">

    <title>@yield('title') {{ config('app.site.separator') . trans('app/site.title') }}</title>

    <!-- Fonts -->
    <link href="http://fonts.useso.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

    <!-- Styles -->
    <link href="{{ elixir('assets/styles.css') }}" rel="stylesheet">
    @stack('styles')

    <script src="{{ url('js/modernizr.custom.js') }}"></script>

    <!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
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
    @stack('scripts.footer')
</body>
</html>

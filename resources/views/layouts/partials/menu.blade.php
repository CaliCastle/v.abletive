@unless(Auth::guest())
<div class="menu-wrap">
    <nav class="menu">
        <div class="profile">
            <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->display_name }}"/>
            <span><a href="{{ auth()->user()->profileLink() }}">{{ str_limit(auth()->user()->display_name, 20) }}</a></span>
            <span class="level"><a href="{{ url('settings/level') }}">{{ auth()->user()->level() }}</a></span>
        </div>
        <div class="link-list">
            <a href="{{ url('settings') }}"><span><i class="fa fa-btn fa-sliders"></i>&nbsp;{{ trans('app/site.menu.settings')  }}</span></a>
            <a href="{{ url('profile') }}"><span><i class="fa fa-btn fa-dashboard"></i>&nbsp;{{ trans('app/site.menu.profile')  }}</span></a>
            <a href="{{ url('favorites') }}"><span><i class="fa fa-btn fa-heart"></i>&nbsp;{{ trans('app/site.menu.favorites')  }}</span></a>
            <a href="{{ url('laters') }}"><span><i class="fa fa-btn fa-clock-o"></i>&nbsp;{{ trans('app/site.menu.watch_laters')  }}</span></a>
            <a href="{{ url('language/') . '/' }}{{ app()->getLocale() == "zh" ? "en" : "zh" }}"><span>{{ trans('app/site.menu.language')  }}</span></a>
        </div>
        <div class="icon-list">
            <a href="{{ url('/') }}"><i class="fa fa-fw fa-home"></i></a>
            <a href="{{ url('faq') }}"><i class="fa fa-fw fa-question-circle"></i></a>
            <a href="javascript:;" id="logout-btn"><i class="fa fa-fw fa-power-off"></i></a>
        </div>
    </nav>
</div>
<button class="menu-button" id="open-button"><i class="fa fa-fw fa-cog"></i><span>Open Menu</span></button>
<div class="background-overlay animated animated-delay2 fadeInLeft"></div>
@push('scripts.footer')
<script>
    (function () {
        'use strict';

        var bodyEl = document.body,
                content = document.querySelector( '.content-wrap' ),
                openbtn = document.getElementById( 'open-button' ),
                closebtn = document.getElementById( 'close-button' ),
                isOpen = false;

        function init() {
            initEvents();
        }

        function initEvents() {
            openbtn.addEventListener( 'click', toggleMenu );
            if( closebtn ) {
                closebtn.addEventListener( 'click', toggleMenu );
            }

            // close the menu element if the target it´s not the menu element or one of its descendants..
            $($('.background-overlay')[0]).on( 'click', function() {
                if( isOpen ) {
                    toggleMenu();
                }
            } );
        }

        function toggleMenu() {
            if( isOpen ) {
                classie.remove( bodyEl, 'show-menu' );
            }
            else {
                classie.add( bodyEl, 'show-menu' );
            }
            isOpen = !isOpen;
        }

        init();

    })();
</script>
@endpush
@endunless
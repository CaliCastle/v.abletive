<nav class="main-nav">
    <ul>
        <li{{ request()->getRequestUri() == "/settings" ? " class=active" : '' }}>
            <a href="{{ url('settings') }}">{{ trans('setting/sidebar.account') }}</a>
            <i class="fa fa-chevron-right"></i>
        </li>
        <li{{ request()->getRequestUri() == "/settings/notification" ? " class=active" : '' }}>
            <a href="{{ url('settings/notification') }}">{{ trans('setting/sidebar.notification') }}</a>
            <i class="fa fa-chevron-right"></i>
        </li>
        <li{{ request()->getRequestUri() == "/settings/subscription" ? " class=active" : '' }}>
            <a href="{{ url('settings/subscription') }}">{{ trans('setting/sidebar.subscription') }}</a>
            <i class="fa fa-chevron-right"></i>
        </li>
        <li{{ request()->getRequestUri() == "/settings/level" ? " class=active" : '' }}>
            <a href="{{ url('settings/level') }}">{{ trans('setting/sidebar.level') }}</a>
            <i class="fa fa-chevron-right"></i>
        </li>
    </ul>
</nav>
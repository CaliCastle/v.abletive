<nav class="main-nav">
    <ul>
        <li{{ str_contains(request()->getRequestUri(), "/publish/lessons") ? " class=active" : '' }}>
            <a href="{{ url('publish/lessons') }}">{{ trans('manage/sidebar.lessons') }}</a>
            <i class="fa fa-chevron-right"></i>
        </li>
    </ul>
</nav>
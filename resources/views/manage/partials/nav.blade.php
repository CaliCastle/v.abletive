<nav class="main-nav">
    <ul>
        <li{{ request()->getRequestUri() == "/manage" ? " class=active" : '' }}>
            <a href="{{ url('manage') }}">{{ trans('manage/sidebar.index') }}</a>
            <i class="fa fa-chevron-right"></i>
        </li>
        <li{{ str_contains(request()->getRequestUri(),"/manage/series") ? " class=active" : '' }}>
            <a href="{{ url('manage/series') }}">{{ trans('manage/sidebar.series') }}</a>
            <i class="fa fa-chevron-right"></i>
        </li>
        <li{{ str_contains(request()->getRequestUri(), "/manage/lessons") ? " class=active" : '' }}>
            <a href="{{ url('manage/lessons') }}">{{ trans('manage/sidebar.lessons') }}</a>
            <i class="fa fa-chevron-right"></i>
        </li>
        <li{{ str_contains(request()->getRequestUri(), "/manage/skills") ? " class=active" : '' }}>
            <a href="{{ url('manage/skills') }}">{{ trans('manage/sidebar.skills') }}</a>
            <i class="fa fa-chevron-right"></i>
        </li>
        <li{{ str_contains(request()->getRequestUri(), "/manage/users") ? " class=active" : '' }}>
            <a href="{{ url('manage/users') }}">{{ trans('manage/sidebar.users') }}</a>
            <i class="fa fa-chevron-right"></i>
        </li>
        <li{{ str_contains(request()->getRequestUri(), "/manage/comments") ? " class=active" : '' }}>
            <a href="{{ url('manage/comments') }}">{{ trans('manage/sidebar.comments') }}</a>
            <i class="fa fa-chevron-right"></i>
        </li>
    </ul>
</nav>
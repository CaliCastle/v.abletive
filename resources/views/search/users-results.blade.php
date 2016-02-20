@if(count($users))<li class="divider" data-content="{{ trans('auth.user') }}"></li>@endif
@foreach($users as $user)
<li class="user-item item">
    <a href="{{ $user->profileLink() }}">
        <img src="{{ $user->avatar }}" alt="{{ $user->display_name }}">
        <span class="meta">
            <h3>{{ $user->display_name }}</h3>
            <p>{{ $user->description }}</p>
        </span>
    </a>
</li>
@endforeach
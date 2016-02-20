<dl>
@forelse($users as $user)
    <dt><img src="{{ $user->avatar }}" alt="{{ $user->display_name }}"></dt>
@empty
@endforelse
</dl>
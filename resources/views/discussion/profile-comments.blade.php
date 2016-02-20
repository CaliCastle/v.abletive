<li>
    <div class="comment-item" data-id="{{ $comment->id }}">
        <div class="avatar">
            <a href="{{ $comment->user->profileLink() }}">
                <img src="{{ $comment->user->avatar }}" alt="{{ $comment->user->display_name }}">
            </a>
        </div>
        <div class="details">
            <div class="meta">
                <strong><a href="{{ $comment->user->profileLink() }}">{{ $comment->user->display_name }}</a></strong>
                <span class="time">{{ $comment->created_at->diffForHumans() }}</span>
                <span class="in-series">{{ trans('profile.in') }}<a href="{{ $comment->lesson->link() }}">{{ $comment->lesson->title }}</a></span>
            </div>
            <div class="body">
                <p>{!! $comment->message !!}</p>
            </div>
        </div>
    </div>
</li>
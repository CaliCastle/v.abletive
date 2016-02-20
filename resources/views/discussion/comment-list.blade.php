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
                @if($comment->user->isManager())
                    <span class="moderator">{{ trans('discussion.manager') }}</span>
                @endif
                @if($comment->user->isTutor())
                    <span class="tutor">{{ trans('discussion.tutor') }}</span>
                @endif
                <span class="time">{{ $comment->created_at->diffForHumans() }}</span>
            </div>
            <div class="body">
                <p>{!! $comment->message !!}</p>
            </div>
            <div class="actions">
                @if(Auth::check())
                <ul class="action-list">
                    <li><a href="javascript:;" id="like-button" title="{{ trans('discussion.like') }}" class="{{ auth()->user()->likedComment($comment) ? "liked" : "" }}">{{ $comment->likes->count() }}</a></li>
                    <li><a href="javascript:;" id="reply-button" title="{{ trans('discussion.reply') }}"><i class="fa fa-btn fa-reply"></i></a></li>
                    <li class="liked-users animated bounceIn"></li>
                </ul>
                @endif
            </div>
        </div>
        @if($comment->children)
        @unless(isset($no_children))
        <ul class="comments-list">
            @each('discussion.comment-list', $comment->children, 'comment')
        </ul>
        @endunless
        @endif
    </div>
</li>
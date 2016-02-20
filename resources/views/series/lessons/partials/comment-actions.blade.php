<div class="comment-actions">
    <div class="myself">
        <img src="{{ auth()->check() ? auth()->user()->avatar : url('assets/images/noavatar.png') }}" alt="" class="avatar">
    </div>
    <div class="reply">
        @if(Auth::guest())
            <div class="textarea guest">
                <h3>{!! trans('discussion.login-message') !!}</h3>
            </div>
        @else
            <div id="reply-textarea" class="textarea" contenteditable data-placeholder="{{ trans('discussion.placeholder') }}"></div>
            <form action="{{ url('comments/upload_image') }}" method="POST" class="dropzone" id="dropzone">{!! csrf_field() !!}</form>
            <div class="reply-actions">
                <a href="javascript:;" id="insert-timecode"><i class="fa fa-btn fa-clock-o"></i>{{ trans('discussion.insert') }}</a>
                <a href="javascript:;" id="insert-image"><i class="fa fa-btn fa-image"></i>{{ trans('discussion.insert_image') }}</a>
                <a href="javascript:;" id="cancel-reply"><i class="fa fa-btn fa-times"></i>{{ trans('discussion.cancel') }}</a>
            </div>
            <div class="reply-button">
                <a href="javascript:;" id="reply-submit">{{ trans('discussion.submit') }}</a>
            </div>
        @endif
    </div>
</div>
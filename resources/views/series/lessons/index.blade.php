@extends('layouts.app')

@section('title', $lesson->title . ' - ' . $series->title)

@push('styles')
<link rel="stylesheet" href="{{ url('css/dropzone.css') }}">
@endpush

@push('scripts.header')
<script src="{{ url('js/video.min.js') }}"></script>
@endpush

@section('content')
    <div class="container">
        <div class="row">
            @include('series.lessons.partials.video-box')
        </div>
        <div class="row">
            @include('series.lessons.partials.series-lessons')
        </div>
    </div>
    <section id="discussions" class="discussions">
        <div class="container">
            <div class="row">
                <h2 class="big-heading">
                    <span class="subtitle">{{ trans('lessons.discussion.heading') }}</span>
                    <span>{{ trans('lessons.discussion.title') }}</span>
                </h2>
            </div>
            <div class="row">
                <div class="comments-wrap">
                    @include('series.lessons.partials.comment-actions')

                    @if($lesson->hasHotComments())
                    <div class="row">
                        <h2 class="big-heading">
                            <span><i class="fa fa-btn fa-fire"></i> {{ trans('discussion.hots') }}</span>
                        </h2>
                    </div>
                    <div class="row">
                        <ul class="hot-comments-list">
                            @foreach($lesson->hotComments() as $hotComment)
                                @include('discussion.comment-list', ["comment" => $hotComment, "no_children" => 'true'])
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="row">
                        <h2 class="big-heading">
                            <span><i class="fa fa-chevron-down"></i></span>
                        </h2>
                    </div>
                    <div class="row">
                        <ul class="comments-list">
                            @forelse($comments as $comment)
                                @include('discussion.comment-list')
                            @empty
                                <h3 class="no-result">{{ trans('messages.no_result', ["name" => trans('discussion.comment')]) }}</h3>
                            @endforelse
                        </ul>
                    </div>
                    <div class="row text-center">
                        @unless($comments->count() <= $comments->perPage())
                        <a href="javascript:;" id="load-more-button" class="btn btn-primary" style="padding: 10px 5em;">{{ trans('discussion.load_more') }}</a>
                        @endunless
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="comment-validation" style="display: none;">
        <h2 class="animated fadeInDown">{{ trans('discussion.validation') }}</h2>
        <div class="validate animated animated-delay4 bounceIn" id="flip-validation"></div>
        <a href="javascript:;" id="submit-validation" class="animated animated-delay2 fadeInUp">{{ trans('messages.confirm_button') }}</a>
        {{--<a href="javascript:;" id="cancel-validation" class="animated animated-delay2 fadeInUp">{{ trans('messages.cancel_button') }}</a>--}}
    </div>
@stop

@push('scripts.footer')
<script src="{{ url('js/dropzone.js') }}"></script>
<script src="{{ url('js/hexaflip.js') }}"></script>
<script>
    $(function () {
        var $reply_area = $($('#reply-textarea')[0]);
        var $parent_id = 0;
        var $_token = "{{ csrf_token() }}";
        var $is_submitting = false;
        var $submit_button = $('a#reply-submit');
        var $current_page = 1;
        var $is_loading = false;
        var $loading_text = "{{ trans('discussion.load_more') }}";
        var $valiadtion = $('.comment-validation')[0];
        var $validateCubes;
        var $gained_xp = false;

        @if(!$lesson->needSubscription())
        var player = videojs('abletive-video', { "aspectRatio":"160:95", "playbackRates": [0.5, 0.75, 1, 1.5, 2, 3] }, function () {

            @if(request()->query('autoplay'))
            this.play();
            @endif

            @unless(Auth::guest())
            @unless(auth()->user()->hasWatched($lesson))
            this.on('ended', function () {
                if ($gained_xp)
                        return false;
                // When the video completed playing
                $.ajax({
                    url: "{{ url('lessons/completed') }}/{{ $lesson->id }}",
                    type: "PUT",
                    data: {_token: "{{ csrf_token() }}"},
                    dataType: "json",
                    success: function (data) {
                        showGenieMessage(data.message);
                        $gained_xp = true;
                        $('span#user-experience').html(data.xp);
                        $('span#video-status').html('<i class="fa fa-check-circle"></i> {{ trans('lessons.status.completed') }}');
                        $($('span#video-status').parent()).addClass('completed animated rubberBand');

                        if (data.level_up != "no") {
                            showStatusMessage(data.level_up);
                        }
                    }
                });
            });
            @endunless
            @endunless
        });
        @else
            @if(auth()->check())
            @if(auth()->user()->validSubscription())
                var player = videojs('abletive-video', { "aspectRatio":"160:95", "playbackRates": [0.5, 0.75, 1, 1.5, 2, 3] }, function () {

                    @if(request()->query('autoplay'))
                            this.play();
                    @endif
                            @unless(Auth::guest())
                            @unless(auth()->user()->hasWatched($lesson))
                            this.on('ended', function () {
                        if ($gained_xp)
                            return false;
                        // When the video completed playing
                        $.ajax({
                            url: "{{ url('lessons/completed') }}/{{ $lesson->id }}",
                            type: "PUT",
                            data: {_token: "{{ csrf_token() }}"},
                            dataType: "json",
                            success: function (data) {
                                showGenieMessage(data.message);
                                $gained_xp = true;
                                $('span#user-experience').html(data.xp);
                                $('span#video-status').html('<i class="fa fa-check-circle"></i> {{ trans('lessons.status.completed') }}');
                                $($('span#video-status').parent()).addClass('completed animated rubberBand');

                                if (data.level_up != "no") {
                                    showStatusMessage(data.level_up);
                                }
                            }
                        });
                    });
                    @endunless
                    @endunless
                });
            @endif
            @endif
        @endif

        @unless(Auth::guest())

        // Dropzone init
        Dropzone.options.dropzone = {
                init: function () {
                    this.on("success", function (file, data) {
                        if (data.status == "ok") {
                            $(data.html).appendTo($($reply_area));
                            $reply_area.trigger('DOMNodeInserted');
                            this.removeFile(file);
                        }
                    })
                },
                paramName: "image",
                maxFileSize: 2,
                maxFiles: 2,
                acceptedFiles: 'image/*',
                dictDefaultMessage: "{{ trans('messages.upload_message') }}",
                dictFileTooBig: "{{ trans('messages.upload_filesize') }}",
                dictInvalidFileType: "{{ trans('messages.upload_filetype') }}",
                dictMaxFilesExceeded: "{{ trans('messages.upload_files') }}"
        };

        var validate_errors = 0,
            makeObject = function(a){
            var o = {};
            for(var i = 0, l = a.length; i < l; i++){
                o['letter' + i] = a;
            }
            return o;
        },
            getSequence = function(a, reverse, random){
                var o = {}, p;
                for(var i = 0, l = a.length; i < l; i++){
                    if(reverse){
                        p = l - i - 1;
                    }else if(random){
                        p = Math.floor(Math.random() * l);
                    }else{
                        p = i;
                    }
                    o['letter' + i] = a[p];
                }
                return o;
            };

        $validateCubes = new HexaFlip(document.getElementById('flip-validation'), makeObject("CALI".split('')),{
            size: 150,
            margin: 12,
            fontSize: 100,
            perspective: 450
        });

        $validateCubes.setValue(getSequence("CALI", false, true));

        $('a#submit-validation').click(function () {
            if (validate_errors >= 3) {
                validate_errors = 0;
                $($valiadtion).fadeOut();
                return false;
            }

            if ($validateCubes.getValue().join('') != "CALI") {
                validate_errors++;
                $validateCubes.setValue(getSequence("CALI", false, true));
            } else {
                $($valiadtion).fadeOut();
                submitComment();
            }
        });

        $($valiadtion).dblclick(function (e) {
            if (e.target == this) {
                $($valiadtion).fadeOut();
            }
        });

        // Watch later actions
        $('a#watch-later-btn').each(function () {
            var el = $(this);
            $(this).click(function () {
                $id = $($(this).parents('ul')[0]).attr('video-id');
                $.ajax({
                    url: "{{ url('lessons/watch_later') }}/" + $id,
                    type: "POST",
                    data: {_token: $_token},
                    dataType: "json",
                    success: function (data) {
                        el.toggleClass('active');
                        swal({title:data.message, type:"success",timer: 1500,showConfirmButton: false});
                    }
                });
            });
        });
        $('a#watch-later-btn-icon').each(function () {
            var el = $(this);
            $(this).click(function () {
                $id = $($(this).parents('li')[0]).attr('video-id');
                $.ajax({
                    url: "{{ url('lessons/watch_later') }}/" + $id,
                    type: "POST",
                    data: {_token: $_token},
                    dataType: "json",
                    success: function (data) {
                        el.toggleClass('active');
                        swal({title:data.message, type:"success",timer: 1500,showConfirmButton: false});
                    }
                });
            });
        });

        // Favorite action
        $('a#favorite-btn').click(function () {
            var el = $(this);
            $id = $($(this).parents('ul')[0]).attr('video-id');
            $.ajax({
                url: "{{ url('lessons/favorite') }}/" + $id,
                type: "POST",
                data: {_token: $_token},
                dataType: "json",
                success: function (data) {
                    el.toggleClass('active');
                    swal({title:data.message, type:"success",timer: 1500,showConfirmButton: false});
                }
            });
        });

        // Series notification
        $('a#series-notify-btn').click(function () {
            var el = $(this);
            $.ajax({
                url: "{{ url('notify') }}/{{ $series->id }}",
                type: "POST",
                data: {_token: $_token},
                dataType: "json",
                success: function (data) {
                    el.toggleClass('active');
                    swal({title:data.message, type:"success",timer: 1500,showConfirmButton: false});
                }
            });
        });

        $('a#go-to-discuss').click(function () {
            $('body').animate({
                scrollTop: $('#discussions').offset().top
            }, 800);
        });

        $reply_area.each(function () {
            $(this).bind('DOMNodeInserted', function (e) {
                if ($(e.target).hasClass('textarea')) {
                    $reply_area.attr('data-placeholder', '');
                }
            });
            $(this).keydown(function (e) {
                if ((event.ctrlKey || event.metaKey) && e.which == 13) {
                    event.preventDefault();
                    $('a#reply-submit').trigger('click');
                    console.log('..');
                }
            });
        });

        $('a#insert-timecode').each(function () {
            $(this).click(function () {
                var time = Math.floor(player.currentTime());
                var timecode = '<a id="change-timecode" href="javascript:;" title="{{ trans('discussion.timecode') }}" data-time="' + time + '"><i class="fa fa-btn fa-history"></i></a>';
                $(timecode).appendTo($(this).parent().prev().prev());
                $(this).parent().prev().prev().trigger('DOMNodeInserted');
            });
        });

        $('a#insert-image').click(function () {
            $('#dropzone').toggleClass('show-dropzone');
        });

        function initEvents() {
            $('a#change-timecode').each(function () {
                $(this).click(function () {
                    $('body').animate({
                        scrollTop: $('.video-box').offset().top
                    }, 500);
                    player.currentTime($(this).attr('data-time')).play();
                });
            });
            // Like buttons
            $('a#like-button').each(function () {
                var parentItem = $(this).parents(".comment-item")[0],
                        commentID = $(parentItem).attr('data-id'),
                        commentLikes = $(this).html(),
                        $liked_list = $($($(this).parents('.action-list')[0]).find('.liked-users')[0]);

                $(this).click(function () {
                    if (!$(this).hasClass('liked')) {
                        // Like this
                        var el = $(this);

                        $.ajax({
                            url: "{{ url('comments/like') }}/" + commentID,
                            data: {_token: $_token},
                            dataType: "json",
                            type: "PUT",
                            success: function (data) {
                                if (data.status == "success") {
                                    // Succeeded
                                    commentLikes++;
                                    el.html(commentLikes).addClass('liked');
                                } else {
                                    showGenieMessage(data.message);
                                }
                            }
                        });
                    }
                });

                $(this).mouseenter(function () {
                    if (commentLikes != 0) {
                        // Only load for the ones actually have likes
                        $liked_list.addClass('show');
                        if ($liked_list.html() == "") {
                            // Show the spinner
                            $liked_list.html('<i class="fa fa-spin fa-spinner"></i>');
                            addLikeList($liked_list, commentID);
                        }
                    }
                });

                $(this).mouseleave(function () {
                    $liked_list.removeClass('show');
                });
            });
            // Reply buttons
            $('a#reply-button').each(function () {
                $(this).click(function () {
                    // Reply this
                    var parentItem = $(this).parents(".comment-item")[0],
                            parentNode = $(this).parents(".details")[0],
                            commentID = $(parentItem).attr('data-id');

                    $parent_id = commentID;
                    $(".comment-actions").appendTo($(parentNode)).addClass("replying");
                });
            });

            $('a#cancel-reply').each(function () {
                $(this).click(function () {
                    $(".comment-actions").prependTo($(".comments-wrap")).removeClass("replying");
                    $parent_id = 0;
                });
            });
        }

        $submit_button.on('click', function () {
            $($valiadtion).fadeIn();
        });

        // Load more comments
        $('a#load-more-button').click(function () {
            var $load_more = $("a#load-more-button");
            $load_more.html('<i class="fa fa-spin fa-spinner"></i>"');

            if (!$is_loading) {
                $is_loading = true;
                $.ajax({
                    url: "{{ url('lessons/' . $lesson->id . '/comments') }}/" + $current_page,
                    data: {_token: $_token},
                    type: "POST",
                    dataType: "json",
                    success: function (data) {
                        $is_loading = false;
                        $load_more.html($loading_text);
                        if (data.html == "") {
                            $($load_more).remove();
                        }
                        $(data.html).appendTo($('ul.comments-list')[0]);
                        $current_page++;

                        initEvents();
                    }
                });
            }
        });

        function submitComment() {
            if ($is_submitting) {
                return false;
            }
            // Submit comment
            $content = $reply_area.html();

            if ($content.trim() == "") {
                return false;
            }

            $is_submitting = true;
            $($submit_button).addClass('disabled');

            $.ajax({
                url: "{{ action("UserController@submitLessonComment", $lesson->id) }}",
                data: {_token: $_token, content: $content, parent_id: $parent_id},
                dataType: "json",
                type: "POST",
                success: function (data){
                    $is_submitting = false;
                    $submit_button.removeClass('disabled');
                    if (data.status == "error") {
                        swal({title:data.message, type:"error",timer: 1500,showConfirmButton: false});
                    } else {
                        showGenieMessage(data.message);
                        addComment($parent_id, data.html);
                    }
                }
            });
        }

        function addComment($parent_id, $html) {
            $no_reulst = $('.comments-list h3');
            if (!$parent_id) {
                if ($no_reulst != null) {
                    $no_reulst.remove();
                }
                $($html).appendTo($('.comments-list')[0]).fadeIn();
            } else {
                var selector = '.comment-item[data-id=' + $parent_id + ']';
                $html = "<ul class=\"comments-list\">" + $html + "</ul>";
                $($html).appendTo($(selector)[0]).fadeIn();

                $('a#cancel-reply').trigger('click');
            }
            $reply_area.html('');
            initEvents();
        }

        function addLikeList($selector, $id) {
            $.ajax({
                url: "{{ url('comments/like_list') }}/" + $id,
                type: "POST",
                data: {_token: $_token},
                dataType: "json",
                success: function (data) {
                    $($selector[0]).html(data.html);
                }
            });
        }

        initEvents();
        @endunless
    });
</script>
@endpush
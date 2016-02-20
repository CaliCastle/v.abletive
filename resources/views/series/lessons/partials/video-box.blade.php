<section class="video">
    <h1 class="title">
        {{ $episode }}. {{ $lesson->title }}
        <span class="series-title">
                        <span>{{ trans('lessons.in') }}</span>
            <a href="{{ $series->link() }}">{{ $series->title }}</a>
                    </span>
    </h1>
    <span class="difficulty">{{ trans('lessons.difficulty.' . strtolower($series->difficulty)) }}</span>
    <div class="video-box">
        <div class="video-player">
            @if($lesson->needSubscription())
            @if(auth()->check())
            @if(auth()->user()->validSubscription())
            <video id="abletive-video" class="video-js vjs-default-skin" controls preload="auto">
                <source src="{{ $lesson->source }}" type='video/mp4' />
                <p class="vjs-no-js">
                    {{ trans('lessons.video-unsupported') }}
                </p>
            </video>
            @else
                <div class="need-subscription">
                    <div class="bg"></div>
                    <div class="info">
                        <h1>{{ trans('messages.subscription_heading') }}</h1>
                        <h4>{{ trans('messages.subscription_message') }}</h4>
                        <a href="{{ url('settings/subscription') }}">{{ trans('messages.subscription_button') }}</a>
                    </div>
                </div>
            @endif
            @else
                <div class="need-subscription">
                    <div class="bg"></div>
                    <div class="info">
                        <h1>{{ trans('messages.subscription_heading') }}</h1>
                        <h4>{{ trans('messages.subscription_message') }}</h4>
                        <a href="{{ url('settings/subscription') }}">{{ trans('messages.subscription_button') }}</a>
                    </div>
                </div>
            @endif

            @else
                <video id="abletive-video" class="video-js vjs-default-skin" controls preload="auto">
                    <source src="{{ $lesson->source }}" type='video/mp4' />
                    <p class="vjs-no-js">
                        {{ trans('lessons.video-unsupported') }}
                    </p>
                </video>
            @endif
        </div>
        <div class="lesson-navigation">
            @if($lesson->hasPrevious())
                <div class="previous">
                    <a href="{{ $lesson->previousEpisode()->link() }}?autoplay=1"><i class="fa fa-angle-left"></i><span>{{ $episode - 1 }}. {{ $lesson->previousEpisode()->title }}</span></a>
                </div>
            @endif
            @if($lesson->hasNext())
                <div class="next">
                    <a href="{{ $lesson->nextEpisode()->link() }}?autoplay=1"><span>{{ $episode + 1 }}. {{ $lesson->nextEpisode()->title }}</span><i class="fa fa-angle-right"></i></a>
                </div>
            @endif
        </div>
        <div class="video-details">
            <strong class="meta">{{ trans('lessons.published_on', ["date" => $lesson->published_at->diffForHumans()]) }}</strong>
            <div class="experience">
                <span>{{ $lesson->experience }} {{ trans('lessons.xp') }}</span>
            </div>
            <div class="body">
                <p>{!! $lesson->description !!}</p>
            </div>
            <ul class="video-tags">
                @foreach($lesson->tags as $tag)
                    <li>
                        <a href="{{ $tag->link() }}">{{ $tag->name }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="lesson-tutor">
        <strong>{{ trans('discussion.tutor') }}:</strong>
        <a href="{{ $lesson->user->profileLink() }}" target="_blank"><img src="{{ $lesson->user->avatar }}" alt="{{ $lesson->user->display_name }}"></a>
        <div class="details">
            <h4 class="name"><a href="{{ $lesson->user->profileLink() }}" target="_blank">{{ $lesson->user->display_name }}</a></h4>
            <span class="count">{{ trans('lessons.lessons_count') }}: {{ $lesson->user->lessons->count() }}</span>
        </div>
    </div>
    <div class="action-buttons">
        <ul video-id="{{ $lesson->id }}">
            @if(Auth::check())
            <li><a href="javascript:;" class="action-button{{ auth()->user()->hasWatchLater($lesson) ? " active" : "" }}" id="watch-later-btn"><i class="fa fa-btn fa-clock-o"></i> {{ trans('lessons.watch-later') }}</a></li>
            <li><a href="javascript:;" class="action-button{{ auth()->user()->hasFavorite($lesson) ? " active" : "" }}" id="favorite-btn"><i class="fa fa-btn fa-heart"></i> {{ trans('lessons.favorite') }}</a></li>
            <li><a href="{{ $lesson->downloadLink() }}" download target="_blank" class="action-button"><i class="fa fa-btn fa-cloud-download"></i> {{ trans('lessons.download') }}</a></li>
            @endif
            <li><a href="javascript:;" id="go-to-discuss" class="action-button"><i class="fa fa-btn fa-comments-o"></i> {{ trans('lessons.discuss') }}</a></li>
            @if(Auth::check())
            <li class="status{{ auth()->user()->hasWatched($lesson) ? " completed" : "" }}"><span id="video-status"><i class="fa fa-check-circle"></i> {{ auth()->user()->hasWatched($lesson) ? trans('lessons.status.completed') : trans('lessons.status.incomplete') }}</span></li>
            @endif
        </ul>
    </div>
</section>
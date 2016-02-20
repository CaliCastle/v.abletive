<section class="series-lessons">
    <h2 class="big-heading">
        <span class="subtitle">{{ trans('series.series-lessons') }}</span>
        <span>{{ $series->title }}</span>
    </h2>
    <div class="section row grid">
        <div class="col-lg-3">
            <div class="series-thumbnail animated animated-delay8 fadeInDown">
                <img src="{{ $series->thumbnail }}" alt="{{ $series->title }}">
            </div>
            @unless($series->completed)
            <div class="lesson-actions">
                <ul class="actions">
                    <li class="animated animated-delay11 fadeInRight">
                        <a href="javascript:;" class="action-button{{ auth()->check() ? auth()->user()->hasNotified($series) ? " active" : "" : "" }}" id="series-notify-btn"><i class="fa fa-btn fa-envelope"></i> {{ trans('series.notify') }}</a>
                    </li>
                </ul>
            </div>
            @endunless
        </div>
        <div class="col-lg-9">
            <ul class="lesson-list lesson-list--numbered animated animated-delay7 fadeIn">
                @foreach($series->lessons as $lesson)
                    <li class="lesson-item{{ auth()->check() ? auth()->user()->hasWatched($lesson) ? " lesson--completed" : "" : "" }} animated fadeInUp" video-id="{{ $lesson->id }}">
                        <span class="status"><i class="fa fa-check-circle"></i></span>
                                    <span class="title"><a href="{{ $lesson->link() }}">{{ $lesson->title }}
                                            {!! $lesson->recentlyPublished() ? '<span class="new">' . trans('series.new') . '!</span>' : '' !!}</a></span>
                        <span class="length">{{ $lesson->duration }}</span>
                        @unless(Auth::guest())
                        <span class="watch-later"><a href="javascript:;" id="watch-later-btn-icon" title="{{ trans('series.watch-later') }}"><i class="fa fa-clock-o"></i></a></span>
                        @endunless
                    </li>
                @endforeach
            </ul>
            @unless($series->completed)
            <p class="development">
                {{ trans('series.development') }}
            </p>
            @endunless
        </div>
    </div>
</section>
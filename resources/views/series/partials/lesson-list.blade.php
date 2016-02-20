<ul class="lesson-list lesson-list--numbered animated animated-delay7 fadeIn">
    @forelse($lessons as $lesson)
        <li class="lesson-item{{ auth()->check() ? auth()->user()->hasWatched($lesson) ? " lesson--completed" : "" : "" }} animated fadeInUp" video-id="{{ $lesson->id }}">
            <span class="status"><a href="{{ url('history') }}"><i class="fa fa-check-circle"></i></a></span>
                                <span class="title"><a href="{{ $lesson->link() }}">{{ $lesson->title }}
                                        {!! $lesson->recentlyPublished() ? '<span class="new">' . trans('series.new') . '!</span>' : '' !!}</a></span>
            <span class="length">{{ $lesson->duration }}</span>
            @unless(Auth::guest())
                <span class="watch-later">
                                    <a href="javascript:;" id="watch-later-btn-icon" title="{{ trans('series.watch-later') }}"><i class="fa fa-clock-o"></i></a>
                                </span>
            @endunless
        </li>
    @empty
        <h2 class="no-result">{{ trans('messages.no_result', ["name" => trans('lessons.lesson')]) }}</h2>
    @endforelse
</ul>
<div class="series">
    <span class="series-difficulty">{{ trans('app/site.features.difficulty.' . $series->difficulty) }}</span>
    @if($series->recentlyPublished())
    <span class="series-status">{{ trans('app/site.features.status.new') }}</span>
    @elseif($series->recentlyUpdated())
    <span class="series-status">{{ trans('app/site.features.status.updated') }}</span>
    @endif
    <div class="series-thumbnail">
        <a href="{{ $series->link() }}">
            <img src="{{ $series->thumbnail }}" alt="{{ $series->title }}">
            <div class="series-overlay">
                <i class="fa fa-play-circle"></i>
            </div>
        </a>
    </div>
    <div class="series-details">
        <h3 class="series-title"><a href="{{ $series->link() }}">{{ str_limit($series->title, 40) }}</a></h3>
        <div class="series-count">
            <h3>{{ $series->lessons->count() }}</h3>
            <span>{{ trans('app/site.features.videos') }}</span>
        </div>
    </div>
</div>
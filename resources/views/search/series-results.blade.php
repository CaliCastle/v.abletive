@if(count($allSeries))<li class="divider" data-content="{{ trans('series.title') }}"></li>@endif
@foreach($allSeries as $series)
<li class="series-item item">
    <a href="{{ $series->link() }}">
        <img src="{{ $series->thumbnail }}" alt="{{ $series->title }}">
        <span class="meta">
            <h3>{{ $series->title }}</h3>
            <p>{!! str_limit($series->description, 130) !!}</p>
        </span>
    </a>
</li>
@endforeach
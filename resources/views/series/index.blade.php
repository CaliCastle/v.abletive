@extends('layouts.app')

@section('title', $series->title)

@push('styles')
<style type="text/css">
    .background-image {
        background: url("{{ $series->thumbnail }}") no-repeat center center;
        background-size: cover;
    }
    header.main-header {
        position: relative;
    }
</style>
@endpush

@section('header-content')
    <div class="header-content">
        <div class="container">
            <div class="banner">
                <div class="col-lg-3">
                    <div class="series-thumbnail animated animated-delay1 bounceInDown">
                        <img src="{{ $series->thumbnail }}" alt="{{ $series->title }}">
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="series-details">
                        <h1 class="series-heading animated animated-delay3 fadeInUp">{{ $series->title }}</h1>
                        <p class="series-message animated animated-delay5 fadeInUp">
                            {!! $series->description !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="container">
                <div class="col-lg-12">
                    <ul class="series-status">
                        <li class="animated animated-delay5 fadeInRight">
                            <span>{{ $series->lessons->count() }}</span> {{ trans('series.lessons')  }}
                        </li>
                        <li class="animated animated-delay7 fadeInRight">
                            <span>{{ $series->totalMinutes() }}</span> {{ trans('series.minutes')  }}
                        </li>
                        @unless(Auth::guest())
                        <li class="animated animated-delay8 fadeInRight">
                            {!! trans('series.complete', ["percent" => auth()->user()->completedPercent($series)]) !!}
                        </li>
                        @endunless
                        <li class="experience animated animated-delay8 fadeInLeft">
                            <span>{{ $series->totalExperience() }}</span> {{ trans('series.xp')  }}
                        </li>
                    </ul>
                </div>
            </div>
        </footer>
    </div>
@endsection

@section('content')
    <div class="lessons-wrap">
        <div class="container">
            <div class="row animated animated-delay6 fadeInUp">
                <h2 class="big-heading">
                    <span class="subtitle">{{ trans('series.series-lessons') }}</span>
                    <span>{{ trans('series.callout') }}</span>
                </h2>
            </div>
            <div class="section row grid">
                <div class="col-lg-9">
                    @include('series.partials.lesson-list', ["lessons" => $series->lessons])
                    @unless($series->completed)
                    <p class="development">
                        {{ trans('series.development') }}
                    </p>
                    @endunless
                </div>
                <div class="col-lg-3">
                    <div class="lesson-actions">
                        <ul class="actions" series-id="{{ $series->id }}">
                            @if(Auth::check())
                            <li class="animated animated-delay8 fadeInRight">
                                <a href="javascript:;" id="series-watch-later-btn" class="action-button{{ auth()->user()->hasWatchLaterSeries($series) ? " active" : "" }}"><i class="fa fa-btn fa-clock-o"></i> {{ trans('series.watch-later') }}</a>
                            </li>
                            <li class="animated animated-delay10 fadeInRight">
                                <a href="javascript:;" id="series-favorite-btn" class="action-button{{ auth()->user()->hasFavoriteSeries($series) ? " active" : "" }}"><i class="fa fa-btn fa-heart"></i> {{ trans('series.favorite') }}</a>
                            </li>
                            @unless($series->completed)
                            <li class="animated animated-delay11 fadeInRight">
                                <a href="javascript:;" id="series-notify-btn" class="action-button{{ auth()->user()->hasNotified($series) ? " active" : "" }}"><i class="fa fa-btn fa-envelope"></i> {{ trans('series.notify') }}</a>
                            </li>
                            @endunless
                            @else
                            <li class="animated animated-delay9 fadeInRight">
                                <a href="javascript:;" id="login-btn" class="action-button"><i class="fa fa-btn fa-smile-o"></i> {{ trans('messages.login_first') }}</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('scripts.footer')
<script>
    $(function () {
        var $_token = "{{ csrf_token() }}";
        // Lesson watch later
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
        // Series watch later
        $('a#series-watch-later-btn').click(function () {
            var el = $(this);
            $id = $(el.parents('ul')[0]).attr('series-id');
            var $action = el.hasClass('active');
            var $url = $action ? "{{ url("series/unwatch_later") }}/" : "{{ url('series/watch_later') }}/";
            $.ajax({
                url: $url + $id,
                type: "POST",
                data: {_token: $_token},
                dataType: "json",
                success: function (data) {
                    el.toggleClass('active');
                    swal({title:data.message, type:"success",timer: 1500,showConfirmButton: false});
                }
            });
        });
        // Series favorite
        $('a#series-favorite-btn').click(function () {
            var el = $(this);
            $id = $(el.parents('ul')[0]).attr('series-id');
            var $action = el.hasClass('active');
            var $url = $action ? "{{ url("series/unfavorite") }}/" : "{{ url('series/favorite') }}/";
            $.ajax({
                url: $url + $id,
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
    });
</script>
@endpush
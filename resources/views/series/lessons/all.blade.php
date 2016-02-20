@extends('layouts.app')

@section('title', trans('lessons.title'))

@section('header-content')
    <div class="header-content">
        <div class="container">
            <div class="row text-center section">
                <h1 class="heading animated animated-delay1 fadeInUp">{{ trans('lessons.title') }}</h1>
                <h3 class="callout animated animated-delay3 fadeInUp">{{ trans('lessons.subtitle') }}</h3>
            </div>
        </div>
    </div>
@stop

@section('content')
<section class="main-content">
    <div class="container">
        <div class="row section">
            <div class="col-lg-3">
                <aside class="sidebar">
                    <h3 class="heading-top">{{ trans('lessons.filters') }}</h3>
                    <ul class="filter-list">
                        <li>
                            <h4 class="heading">{{ trans('lessons.difficulties') }}</h4>
                            <span><a href="{{ url('lessons/difficulty') }}/?s=beginner" class="btn btn-block{{ request()->query('s') == "beginner" ? " active" : "" }}">{{ trans('lessons.difficulty.beginner') }}</a></span>
                            <span><a href="{{ url('lessons/difficulty') }}/?s=intermediate" class="btn btn-block{{ request()->query('s') == "intermediate" ? " active" : "" }}">{{ trans('lessons.difficulty.intermediate') }}</a></span>
                            <span><a href="{{ url('lessons/difficulty') }}/?s=advanced" class="btn btn-block{{ request()->query('s') == "advanced" ? " active" : "" }}">{{ trans('lessons.difficulty.advanced') }}</a></span>
                        </li>
                        <li>
                            <h4 class="heading">{{ trans('lessons.measurement') }}</h4>
                            <span><a href="{{ url('lessons/type') }}/?s=hottest" class="btn btn-block{{ request()->query('s') == "hottest" ? " active" : "" }}">{{ trans('lessons.hottest') }}</a></span>
                            <span><a href="{{ url('lessons') }}" class="btn btn-block{{ request()->query('s') == null ? " active" : "" }}">{{ trans('lessons.newest') }}</a></span>
                            <span><a href="{{ url('lessons/type') }}/?s=oldest" class="btn btn-block{{ request()->query('s') == "oldest" ? " active" : "" }}">{{ trans('lessons.oldest') }}</a></span>
                        </li>
                    </ul>
                </aside>
            </div>
            <div class="col-lg-9">
                @include('series.partials.lesson-special-list')
                <div class="row section">
                    <div class="text-center">
                        @unless(request()->query('s') == "hottest" && url()->current() == url('lessons/type'))
                        @if(url()->current() == url('lessons/difficulty'))
                            <p>{!! $lessons->appends(['s' => $which])->links() !!}</p>
                        @else
                            <p>{!! isset($which) ? $lessons->appends(['s' => $which])->links() : $lessons->links() !!}</p>
                        @endif
                        @endunless
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
@extends('layouts.app')

@section('title', "\"" . trans('skills.' . $skill->name) . "\" " . trans('header/navbar.skills'))

@push('styles')
<style type="text/css">
    .background-image {
        background: url("{{ $skill->thumbnail }}") no-repeat center center;
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
                    <div class="skill-thumbnail animated animated-delay1 bounceInDown">
                        <img src="{{ $skill->thumbnail }}" alt="{{ trans('skills.' . $skill->name) }}">
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="skill-details">
                        <h1 class="skill-heading animated animated-delay3 fadeInUp">{{ "<" . trans('skills.' . $skill->name) . "> " . trans('header/navbar.skills') }}</h1>
                        <p class="skill-message animated animated-delay5 fadeInUp">
                            {!! $skill->description !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="container">
                <div class="col-lg-12">
                    <ul class="skill-status">
                        <li class="animated animated-delay5 fadeInRight">
                            <span>{{ $skill->series->count() }}</span> {{ trans('series.title') }}
                        </li>
                        <li class="animated animated-delay7 fadeInRight">
                            <span>{{ $skill->lessonsCount() }}</span> {{ trans('lessons.skill_lessons') }}
                        </li>
                    </ul>
                </div>
            </div>
        </footer>
    </div>
@stop

@section('content')
<section class="main-content">
    <div class="container">
        <div class="row">
            <div class="section">
                <div class="series-collection">
                @forelse($skill->series as $series)
                    @include('series.partials.series-tiles')
                @empty
                    <h2 class="no-result">{{ trans('messages.no_result', ["name" => trans('header/navbar.skills')]) }}</h2>
                @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
@include('layouts.partials.level-up')
@stop
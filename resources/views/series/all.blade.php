@extends('layouts.app')

@section('title', trans('series.title'))

@section('header-content')
    <div class="header-content">
        <div class="container">
            <div class="row text-center section">
                <h1 class="heading animated animated-delay1 fadeInUp">{{ trans('series.title') }}</h1>
                <h3 class="callout animated animated-delay3 fadeInUp">{{ trans('series.subtitle') }}</h3>
            </div>
        </div>
    </div>
@stop

@section('content')
<section class="main-content">
    <div class="container">
        <div class="section">
            <div class="row">
                <div class="series-collection">
                    @foreach($allSeries as $series)
                        @include('series.partials.series-tiles')
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@stop
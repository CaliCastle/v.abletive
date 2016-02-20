@extends('layouts.app')

@section('title', trans('app/pages.index'))

@section('header-content')
    <div class="header-content">
        <div class="container">
            <div class="row">
                <div class="text-center">
                    <h1 class="heading animated fadeInUp">{!! trans('app/site.welcome.heading') !!}</h1>
                    <h3 class="callout animated animated-delay3 fadeInUp">{!! trans('app/site.welcome.callout') !!}</h3>
                    <h3 class="description animated animated-delay5 fadeInUp">{!! trans('app/site.welcome.description') !!}</h3>
                </div>
            </div>
            <div class="row">
                <div class="text-center animated animated-delay6 fadeInUp">
                    <h4 class="start"><a href="{{ url('series') }}" class="btn btn-primary">{!! trans('app/site.welcome.start') !!}</a></h4>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
<section class="main-content">
    <div class="container">
        <div class="row">
            <div class="block-message animated animated-delay8 fadeInUp">
                <h2>{{ trans('app/site.features.block-message') }}</h2>
            </div>
        </div>
        <div class="row">
            <div class="featured-title animated animated-delay10 fadeIn">
                <span><a href="">{{ trans('app/site.features.featured-series') }}</a></span>
            </div>
        </div>
        <div class="row">
            <div class="series-collection">
                @forelse(\App\Series::featured() as $series)
                    @include('series.partials.series-tiles')
                @empty
                    <h2 class="no-result">{{ trans('messages.no_result', ["name" => trans('series.title')]) }}</h2>
                @endforelse
            </div>
        </div>
        <div class="row">
            <div class="featured-title">
                <span><a href="">{{ trans('app/site.features.featured-skills') }}</a></span>
            </div>
        </div>
        <div class="row">
            <div class="skills-collection">
                @foreach(\App\Skill::all() as $skill)
                    <div class="skill">
                        <a href="{{ url('skills/' . $skill->name) }}">
                            <img src="{{ $skill->thumbnail }}" alt="">
                        </a>
                        <span>{{ trans('skills.' . $skill->name) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@include('layouts.partials.level-up')
<section class="testimonials">
    <div class="container">
        <div class="row">
            <div class="block-message">
                <h2>{{ trans('app/site.testimonials.block-message') }}</h2>
            </div>
        </div>
        <div class="row">
            <div class="featured-title">
                <span>{{ trans('app/site.testimonials.featured-title') }}</span>
            </div>
        </div>
        <div class="row">
            <div class="testimonials-collection">
                @for($i = 1; $i <= 10; $i++)
                    <div class="testimonial">
                        @if(trans("testimonials.{$i}.link") != "testimonials.${i}.link")
                            <a href="{{ trans("testimonials.{$i}.link") }}" target="_blank"><img class="avatar" src="{{ url('assets/images/testimonials') . '/' . trans("testimonials.{$i}.avatar") }}" alt=""></a>
                        @else
                            <img class="avatar" src="{{ url('assets/images/testimonials') . '/' . trans("testimonials.{$i}.avatar") }}" alt="">
                        @endif
                        <h4 class="name">{{ trans("testimonials.{$i}.name") }}</h4>
                        <h5 class="caption">{{ trans("testimonials.{$i}.caption") }}</h5>
                        <blockquote class="message">{{ trans("testimonials.{$i}.message") }}</blockquote>
                    </div>
                @endfor
            </div>
        </div>
        <div class="row">
            <div class="see-more">
                <span><a href="{{ url('testimonials') }}">{{ trans('testimonials.see-more') }} <i class="fa fa-btn fa-chevron-right"></i></a></span>
            </div>
        </div>
    </div>
</section>
@endsection

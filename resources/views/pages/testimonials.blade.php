@extends('layouts.app')

@section('title', trans('app/site.testimonials.block-message'))

@section('header-content')
    <div class="header-content">
        <div class="container">
            <div class="row section">
                <div class="text-center">
                    <h1 class="heading animated animated-delay1 fadeInUp">{{ trans('app/site.testimonials.block-message') }}</h1>
                    <h3 class="callout animated animated-delay3 fadeInUp">{{ trans('app/site.testimonials.featured-title') }}</h3>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <section class="testimonials">
        <div class="container">
            <div class="section">
                <div class="row" style="margin: 50px 0">
                    <div class="testimonials-collection">
                        @for($i = 1; $i < count(trans('testimonials')); $i++)
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
            </div>
        </div>
    </section>
@stop
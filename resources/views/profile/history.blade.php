@extends('layouts.app')

@section('title', trans('profile.history_title'))

@section('content')
    <div class="container">
        <section class="timeline">
            <div class="row">
                <h2 class="big-heading">
                    <span class="subtitle">{{ trans('profile.history_subtitle') }}</span>
                    <span>{{ trans('profile.history_title') }}</span>
                </h2>
            </div>
            @if(count($lessons))
            <ul class="cbp_tmtimeline">
                @foreach($lessons as $lesson)
                <li class="animated bounceInUp">
                    <time class="cbp_tmtime" datetime="{{ $time_list[$lesson->id] }}"><span>{{ $time_list[$lesson->id]->diffForHumans() }}</span> <span>{{ $time_list[$lesson->id]->format('H:i') }}</span></time>
                    <div class="cbp_tmicon"></div>
                    <div class="cbp_tmlabel">
                        <h2>{{ trans('profile.watched') }} <a target="_blank" href="{{ $lesson->link() }}">{{ $lesson->title }}</a></h2>
                        <p>{{ trans('profile.from_series') }}《<a href="{{ $lesson->series->link() }}" target="_blank">{{ $lesson->series->title }}</a>》. {{ trans('profile.tutor') }} {{ $lesson->user->display_name }}</p>
                        <p>{{ trans('profile.gain_xp') }} {{ $lesson->experience }}</p>
                        <p>{{ trans('profile.lesson_description') }}{!! $lesson->description !!}</p>
                    </div>
                </li>
                @endforeach
            </ul>
            @else
            <h3 class="no-result">{{ trans('messages.no_result', ["name" => trans('profile.history')]) }}</h3>
            @endif
        </section>
    </div>
@stop
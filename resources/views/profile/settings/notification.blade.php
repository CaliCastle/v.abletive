@extends('layouts.app')

@section('title', trans('setting/notification.title'))

@section('content')
    <div class="container">
        <section class="setting flex-container">
            @include('profile.settings.partials.nav')
            <aside class="box-right">
                <div class="content">
                    <h2 class="heading">{{ trans('setting/notification.title') }}</h2>
                    <form action="{{ url('settings/notification') }}/{{ auth()->user()->subscribed() ? "unsubscribe" : "subscribe" }}" method="POST" class="setting-form">
                        <div class="form-group">
                            <blockquote>{{ auth()->user()->subscribed() ? trans('setting/notification.explanation') : trans('setting/notification.promotion') }}</blockquote>
                        </div>
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <input type="submit" class="btn btn-block btn-{{ auth()->user()->subscribed() ? "danger" : "primary" }}"
                                   value="{{ auth()->user()->subscribed() ? trans('setting/notification.unsubscribe') : trans('setting/notification.subscribe') }}">
                        </div>
                    </form>
                    @if(auth()->user()->subscribed())
                        <div class="row">
                            <h2 class="big-heading">
                                <span>{{ trans('setting/notification.my_notifs') }}</span>
                            </h2>
                            <ul class="subscription-list">
                                @forelse(auth()->user()->notifications as $series)
                                <li>
                                    <form action="{{ url('series/cancel') }}/{{ $series->id }}" method="POST" class="setting-form">
                                        {!! csrf_field() !!}
                                        <div class="form-group subscription-group">
                                            <a target="_blank" href="{{ $series->link() }}" class="subscription-lesson">{{ $series->title }}</a>
                                            <input class="btn btn-block btn-danger" type="submit" value="{{ trans('setting/notification.cancel') }}">
                                        </div>
                                    </form>
                                </li>
                                @empty
                                    <h2 class="no-result">{{ trans('messages.no_result', ["name" => trans('setting/notification.subscription')]) }}</h2>
                                @endforelse
                            </ul>
                        </div>
                    @endif
                </div>
            </aside>
        </section>
    </div>
@stop
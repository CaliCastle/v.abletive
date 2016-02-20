@extends('layouts.app')

@section('title', trans('profile.title', ["name" => $user->display_name]))

@section('header-content')
    <div class="header-content">
        <div class="container">
            <div class="banner">
                <div class="col-lg-10">
                    <div class="avatar animated animated-delay1 fadeInRight">
                        <img src="{{ $user->avatar }}" alt="{{ $user->display_name }}">
                    </div>
                    <div class="meta animated animated-delay4 fadeInUp">
                        <h2 class="name">{{ $user->display_name }}</h2>
                        <p class="description">{{ $user->description }}</p>
                        @if(Auth::check())
                            @if(Auth::user()->id == $user->id)
                                <a href="{{ url('settings') }}" class="edit-button">{{ trans('profile.edit') }}</a>
                                <a href="{{ url('update-account') }}" class="edit-button"><i class="fa fa-btn fa-repeat"></i> {{ trans('profile.update') }}</a>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="experience animated animated-delay6 bounceInRight">
                        <span class="number">{{ $user->experience }}</span>
                        <span>{{ trans('profile.xp') }}</span>
                        <b>{{ $user->level() }}</b>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="container">
                <div class="col-lg-12">
                    <ul class="status">
                        <li class="animated animated-delay5 fadeInLeft">
                            <span><i class="fa fa-btn fa-archive"></i> {!! trans('profile.member-since', ['date' => $user->registered_at->diffForHumans()]) !!}</span>
                        </li>
                        <li class="animated animated-delay8 fadeInLeft"><span><i class="fa fa-btn fa-street-view"></i> {!! $user->expired() ? trans('profile.membership-expired') : trans('profile.membership-since', ['date' => $user->expired_at->diffForHumans()]) !!}</span></li>
                        <li class="animated animated-delay10 fadeInLeft">
                            <span><i class="fa fa-btn fa-heart"></i> <strong>{{ $user->favoriteLessons->count() }}</strong>&nbsp;{{ trans('profile.favorites') }}</span>
                        </li>
                        <li class="animated animated-delay11 fadeInLeft">
                            <span><i class="fa fa-btn fa-check"></i> {!! trans('profile.completed', ["number" => $user->watchedLessons->count()]) !!}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </footer>
    </div>
@stop

@section('content')
    <div class="container main-content">
        <section class="discussion">
            <div class="row">
                <h2 class="big-heading">
                    <span class="subtitle">{{ trans('profile.callout') }}</span>
                    <span>{{ trans('profile.discussion', ["name" => $user->display_name]) }}</span>
                </h2>
            </div>
            <div class="row">
                <ul class="comments-list">
                @forelse($user->profileComments() as $comment)
                        @include('discussion.profile-comments')
                @empty
                    <h4 class="empty">{{ trans('profile.empty-discussion') }}</h4>
                @endforelse
                </ul>
            </div>
        </section>
        @if($user->isTutor() || $user->isManager())
        <section class="lesson">
            <div class="row">
                <h2 class="big-heading">
                    <span>{{ trans('profile.lesson', ["name" => $user->display_name]) }}</span>
                </h2>
            </div>
            <div class="row">
                <div class="col-lg-10 col-lg-offset-1">
                    @include('series.partials.profile-lessons', ["lessons" => $user->profileLessons()])
                </div>
            </div>
        </section>
        @endif
    </div>
@stop
@extends('layouts.app')

@section('title', trans('setting/subscription.title'))

@section('content')
    <div class="container">
        <section class="setting flex-container">
            @include('profile.settings.partials.nav')
            <aside class="box-right">
                <div class="content">
                    <h2 class="heading">{{ trans('setting/subscription.title') }}</h2>
                    <form action="" method="POST" class="setting-form">
                        <div class="form-group">
                            <label>{!! auth()->user()->expired() ? trans('profile.membership-expired') : trans('profile.membership-since', ["date" => auth()->user()->expired_at->diffForHumans()]) !!}</label>
                        </div>
                        <div class="form-group">
                            <p>{!! trans('setting/subscription.other', ["link" => "http://abletive.com/author/" . auth()->user()->user_id . "?tab=membership"]) !!}</p>
                            <p>{{ trans('setting/account.sync-message') }} <a href="{{ url('update-account') }}">{{ trans('setting/account.sync-link') }}</a></p>
                        </div>
                    </form>
                </div>
            </aside>
        </section>
    </div>
@stop
@extends('layouts.app')

@section('title', trans('setting/account.title'))

@section('content')
    <div class="container">
        <section class="setting flex-container">
            @include('profile.settings.partials.nav')
            <aside class="box-right">
                <div class="content">
                    <h2 class="heading">{{ trans('setting/account.title') }}</h2>
                    <form action="{{ url('settings') }}" method="POST" class="setting-form">
                        {!! csrf_field() !!}
                        <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
                            <label for="slug">{{ trans('setting/account.slug') }}:</label>
                            <div class="input-group">
                                <div class="input-group-addon">@</div>
                                <input type="text" class="form-control" name="slug" value="{{ auth()->user()->slug }}">
                            </div>
                            @if($errors->has('slug'))
                                <strong class="has-error">{{ $errors->first('slug') }}</strong>
                            @endif
                            <strong></strong>
                        </div>
                        <div class="form-group{{ $errors->has('display_name') ? ' has-error' : '' }}">
                            <label for="display_name">{{ trans('setting/account.display_name') }}:</label>
                            <input class="form-control" type="text" name="display_name" value="{{ auth()->user()->display_name }}">
                            @if($errors->has('display_name'))
                                <strong class="has-error">{{ $errors->first('display_name') }}</strong>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email">Email:</label>
                            <input class="form-control" type="email" name="email" value="{{ auth()->user()->email }}">
                            @if($errors->has('email'))
                                <strong class="has-error">{{ $errors->first('email') }}</strong>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label for="description">{{ trans('setting/account.description') }}:</label>
                            <textarea name="description" class="form-control" id="">{{ auth()->user()->description }}</textarea>
                            @if($errors->has('description'))
                                <strong class="has-error">{{ $errors->first('description') }}</strong>
                            @endif
                        </div>
                        <div class="form-divider"></div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password">{{ trans('setting/account.password') }}:</label>
                            <b style="display: block;">{{ trans('setting/account.password-tip') }}</b>
                            <input class="form-control" type="password" name="password" placeholder="{{ trans('setting/account.password_placeholder') }}">
                            @if($errors->has('password'))
                                <strong class="has-error">{{ $errors->first('password') }}</strong>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('confirm_password') ? ' has-error' : '' }}">
                            <input class="form-control" type="password" name="password_confirmation" placeholder="{{ trans('setting/account.confirm_password') }}...">
                        </div>
                        <div class="form-group">
                            <input class="btn btn-block btn-primary" type="submit" value="{{ trans('setting/account.update') }}">
                        </div>
                        <div class="form-group">
                            <p>{!! trans('setting/account.other', ["link" => "http://abletive.com/author/" . auth()->user()->user_id . "?tab=profile"]) !!}</p>
                            <p>{{ trans('setting/account.sync-message') }} <a href="{{ url('update-account') }}">{{ trans('setting/account.sync-link') }}</a></p>
                        </div>
                    </form>
                </div>
            </aside>
        </section>
    </div>
@stop
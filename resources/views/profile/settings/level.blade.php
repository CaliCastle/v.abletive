@extends('layouts.app')

@section('title', trans('setting/level.title'))

@section('content')
    <div class="container">
        <section class="setting flex-container">
            @include('profile.settings.partials.nav')
            <aside class="box-right">
                <div class="content">
                    <h2 class="heading">{{ trans('setting/level.title') }}</h2>
                    <h2 class="big-heading">
                        <span class="subtitle">{{ auth()->user()->level() }}</span>
                        <span>{{ trans('setting/level.your-xp', ['xp' => auth()->user()->experience]) }}</span>
                    </h2>
                    <div class="xp-table">
                        <div class="row">
                            <div class="left"><span>{{ trans('setting/level.level-name') }}</span></div>
                            <div class="right"><span>{{ trans('setting/level.level-xp') }}</span></div>
                        </div>
                        @foreach($levels as $level)
                            <div class="row{{ $level->name === auth()->user()->level() ? " active" : "" }}">
                                <div class="left">
                                    <span>{{ trans('app/levels.' . $level->name) }}</span>
                                </div>
                                <div class="right">
                                    <span>{{ number_format($level->experience) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </aside>
        </section>
    </div>
@stop
@extends('layouts.app')

@section('title', trans('manage/index.title'))

@section('content')
    <div class="container">
        <section class="setting flex-container">
            @include('manage.partials.nav')
            <aside class="box-right">
                <div class="content">
                    <h2 class="heading">{{ trans('manage/index.title') }}</h2>
                    <h2 class="big-heading">
                        <span class="subtitle">今日新增用户</span>
                        <span>{{ \App\User::justRegistered()->count() }}</span>
                    </h2>
                    <h2 class="big-heading">
                        <span class="subtitle">用户总计</span>
                        <span>{{ \App\User::all()->count() }}</span>
                    </h2>
                    <div class="row">
                        <h2 class="big-heading">
                            <span class="subtitle">总计教程系列</span>
                            <span>{{ \App\Series::all()->count() }}</span>
                        </h2>
                        <h2 class="big-heading">
                            <span class="subtitle">总计课程</span>
                            <span>{{ \App\Video::all()->count() }}</span>
                        </h2>
                    </div>
                </div>
            </aside>
        </section>
    </div>
@stop
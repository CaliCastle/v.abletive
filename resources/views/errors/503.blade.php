@extends('layouts.app')

@section('title', strip_tags(trans('app/site.503')))

@section('header-content')
    <div class="header-content">
        <div class="container animated animated-delay1 bounceIn">
            <div class="row">
                <div class="nothing-found text-center">
                    <h1><i class="fa fa-toggle-on"></i></h1>
                    <h1>{!! trans('app/site.503') !!}</h1>
                </div>
            </div>
        </div>
    </div>
@stop
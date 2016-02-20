@extends('layouts.app')

@section('title', '404')

@section('header-content')
    <div class="header-content">
        <div class="container animated animated-delay1 bounceIn">
            <div class="row">
                <div class="nothing-found text-center">
                    <h1><i class="fa fa-meh-o"></i> 404</h1>
                    <h1>{!! trans('app/site.404') !!}</h1>
                </div>
            </div>
        </div>
    </div>
@stop
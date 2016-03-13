@extends('emails.base')

@section('title', "《".$series->title."》有更新")

@section('content')
    <h3>系列课程更新啦!</h3>
    <b><a href="{{ $series->link() }}" target="_blank"><img src="{{ $series->thumbnail }}" alt="{{ $series->title }}"></a></b>
    <p>嗨, <strong>{{ $user->display_name }}</strong>! - </p>
    <strong>您所订阅的《<strong><a href="{{ $series->link() }}" target="_blank">{{ $series->title }}</a></strong>》现在更新了课程噢!</strong>
    <h3 style="text-align:center;"><a href="{{ $lesson->link() }}" target="_blank" style="color: #eee; text-decoration: none;">{{ $lesson->episode() }}.{{ $lesson->title }}</a></h3>
    <h4>课程介绍: {!! $lesson->description !!}</h4>
@stop
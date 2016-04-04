@extends('emails.base')

@section('title', "全新教程系列《".$series->title."》发布了")

@section('content')
    <h3>《{{ $series->title }}》系列课程发布啦!</h3>
    <b><a href="{{ $series->link() }}" target="_blank"><img src="{{ $series->thumbnail }}" alt="{{ $series->title }}"></a></b>
    <p>嗨, <strong>{{ $user->display_name }}</strong>! - </p>
    <h4>系列课程介绍: {!! $series->description !!}</h4>
    <p>该课程难度等级为: "{{ trans('lessons.difficulty.' . strtolower($series->difficulty)) }}"</p>
@stop
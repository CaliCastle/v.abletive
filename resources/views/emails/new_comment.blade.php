@extends('emails.base')

@section('title', $sender->display_name . "在《{$lesson->title}》中回复了您:")

@section('content')
    <h2 style="text-align:center;">{{ $sender->display_name . "在《{$lesson->title}》中回复了您:" }}</h2>
    <strong><img class="avatar" src="{{ $sender->avatar }}" alt="{{ $sender->display_name }}的头像">:{!! $content !!}</strong>
    <p>点击下面的链接前往查看:</p>
    <h3 style="text-align:center;"><a href="{{ $lesson->link() }}" target="_blank" style="color: #fff; text-decoration: none;">{{ $lesson->episode() }}.{{ $lesson->title }}</a></h3>
    <b><a href="{{ $lesson->series->link() }}" target="_blank"><img src="{{ $lesson->series->thumbnail }}" alt="{{ $lesson->series->title }}"></a></b>
@stop
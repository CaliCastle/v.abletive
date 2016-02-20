@extends('layouts.app')

@section('title', trans('tags.title'))

@section('header-content')
    <div class="header-content">
        <div class="container">
            <div class="row text-center section">
                <h1 class="heading animated animated-delay1 fadeInUp">{{ trans('tags.title') }}</h1>
                <h3 class="callout animated animated-delay3 fadeInUp">{{ trans('tags.subtitle') }}</h3>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container">
        <section class="tags section">
            <div class="row">
                <ul class="tag-list animated animated-delay5 bounceIn">
                    @forelse($tags as $tag)
                        <li class="tag"><a href="{{ $tag->link() }}">{{ $tag->name }}</a></li>
                    @empty
                        <h2 class="no-result"></h2>
                    @endforelse
                </ul>
            </div>
        </section>
    </div>
@stop
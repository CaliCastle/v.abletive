@extends('layouts.app')

@section('title', trans('tags.detail_title', ["name" => $tag->name]))

@section('content')
    <div class="container">
        <section class="tags section">
            <div class="row">
                <h2 class="big-heading">
                    <span class="subtitle">{{ trans('tags.detail_subtitle', ["count" => $tag->pagedLessons()->total()]) }}</span>
                    <span>{{ trans('tags.detail_title', ["name" => $tag->name]) }}</span>
                </h2>
            </div>
            <div class="row">
                <div class="col-lg-10 col-lg-offset-1">
                    @include('series.partials.lesson-special-list', ["lessons" => $tag->pagedLessons()])
                </div>
            </div>
            <div class="row">
                <div class="col-lg-10 col-lg-offset-1 text-center">
                    <p>
                        {!! $tag->pagedLessons()->links() !!}
                    </p>
                </div>
            </div>
        </section>
    </div>
@stop
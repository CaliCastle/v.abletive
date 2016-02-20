@extends('layouts.app')

@section('title', trans('manage/skills.edit_title'))

@section('content')
    <div class="container">
        <section class="setting flex-container">
            @include('manage.partials.nav')
            <aside class="box-right">
                <div class="content">
                    <h2 class="heading">{{ trans('manage/skills.title') }}</h2>
                    <div class="row">
                        <form action="{{ action('ManageController@updateSkill', ["id" => $skill->id]) }}" method="POST" class="setting-form">
                            {!! csrf_field() !!}
                            <div class="form-group">
                                <label>{{ trans('manage/skills.name') }}</label>
                                <input class="form-control" type="text" name="name" value="{{ $skill->name }}" required>
                            </div>
                            <div class="form-group">
                                <label>{{ trans('manage/skills.thumbnail') }}</label>
                                <input class="form-control" type="text" name="thumbnail" value="{{ $skill->thumbnail }}" required>
                            </div>
                            <div class="form-group">
                                <label>{{ trans('manage/skills.description') }}</label>
                                <textarea class="form-control" name="description">{!! $skill->description !!}</textarea>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-block btn-primary" value="{{ trans('manage/skills.update') }}">
                            </div>
                        </form>
                    </div>
                </div>
            </aside>
        </section>
    </div>
@stop
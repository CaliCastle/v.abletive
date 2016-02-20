@extends('layouts.app')

@section('title', trans('manage/skills.title'))

@section('content')
    <div class="container">
        <section class="setting flex-container">
            @include('manage.partials.nav')
            <aside class="box-right">
                <div class="content">
                    <h2 class="heading">{{ trans('manage/skills.title') }}</h2>
                    <div class="row">
                        @if(count($skills))
                            <table class="table table-striped table-responsive">
                                <thead>
                                <tr>
                                    <td>{{ trans('manage/skills.name') }}</td>
                                    <td>{{ trans('manage/skills.series') }}</td>
                                    <td></td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($skills as $skill)
                                    <tr>
                                        <td>{{ trans('skills.' . $skill->name) }}</td>
                                        <td>{{ $skill->series->count() }}</td>
                                        <td><a href="{{ action('ManageController@showEditSkill', ["id" => $skill->id]) }}" class="btn btn-primary">{{ trans('manage/skills.edit') }}</a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <h3 class="no-result">{{ trans('messages.no_result', ["name" => trans('skills.skill')]) }}</h3>
                        @endif
                    </div>
                </div>
            </aside>
        </section>
    </div>
@stop
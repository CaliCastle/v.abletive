@extends('layouts.app')

@section('title', '编辑考试')

@section('content')
    <div class="container">
        <section class="setting flex-container">
            @include('manage.partials.nav')
            <aside class="box-right">
                <div class="content">
                    <h2 class="heading">编辑考试</h2>
                    @include('manage.examination.partials.form', [
                       "url" => action('ManageController@updateExamination', ["id" => $examination->id]),
                       "button_text" => "更新"])
                </div>
            </aside>
        </section>
    </div>
@stop
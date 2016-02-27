@extends('layouts.app')

@section('title', '新增问题')

@section('content')
    <div class="container">
        <section class="setting flex-container">
            @include('manage.partials.nav')
            <aside class="box-right">
                <div class="content">
                    <h2 class="heading">新增问题</h2>
                    @include('manage.examination.questions.partials.form', [
                       "url" => url()->current(),
                       "button_text" => "新增",
                       "question" => new \App\Question])
                </div>
            </aside>
        </section>
    </div>
@stop
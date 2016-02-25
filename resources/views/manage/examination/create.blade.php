@extends('layouts.app')

@section('title', '新增考试')

@section('content')
    <div class="container">
        <section class="setting flex-container">
            @include('manage.partials.nav')
            <aside class="box-right">
                <div class="content">
                    <h2 class="heading">新增考试</h2>
                    @include('manage.examination.partials.form', [
                       "url" => url('manage/examination/create'),
                       "button_text" => "新增"])
                </div>
            </aside>
        </section>
    </div>
@stop
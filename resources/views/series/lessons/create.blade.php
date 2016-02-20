@extends('layouts.app')

@section('title', trans('manage/lessons.create_title'))

@push('styles')
<link rel="stylesheet" href="{{ url('css/select2.min.css') }}">
@endpush

@section('content')
    <div class="container">
        <section class="setting flex-container">
            @include('series.partials.nav')
            <aside class="box-right">
                <div class="content">
                    <h2 class="heading">{{ trans('manage/lessons.create_title') }}</h2>
                    @include('manage.lesson.partials.form', [
                       "url" => url('publish/lessons/create'),
                       "button_text" => trans('manage/lessons.create_button'),
                       "type" => "create"])
                </div>
            </aside>
        </section>
    </div>
@stop

@push('scripts.footer')
<script src="{{ url('js/select2.min.js') }}"></script>
<script>
    $(function () {
        $('#xp-select').select2();
        $('#series-select').select2();
        $('#tag-select').select2({
            tags: true,
            tokenSeparators: [',', ' ']
        });
    });
</script>
@endpush
@extends('layouts.app')

@section('title', trans('manage/series.create_title'))

@push('styles')
<link rel="stylesheet" href="{{ url('css/select2.min.css') }}">
@endpush

@section('content')
    <div class="container">
        <section class="setting flex-container">
            @include('manage.partials.nav')
            <aside class="box-right">
                <div class="content">
                    <h2 class="heading">{{ trans('manage/series.create_title') }}</h2>
                    @include('manage.series.partials.form',[
                        "url" => url('manage/series/create'),
                        "button_text" => trans('manage/series.create_button')])
                </div>
            </aside>
        </section>
    </div>
@stop


@push('scripts.footer')
<script src="{{ url('js/select2.min.js') }}"></script>
<script>
    $(function () {
        $('#skill-select').select2();
    });
</script>
@endpush
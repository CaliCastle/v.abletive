@extends('layouts.app')

@section('title', isset($keyword) ? trans('manage/lessons.search-title', ["keyword" => $keyword]) : trans('manage/lessons.title'))

@section('content')
    <div class="container">
        <section class="setting flex-container">
            @include('manage.partials.nav')
            <aside class="box-right">
                <div class="content">
                    <h2 class="heading">{{ isset($keyword) ? trans('manage/lessons.search-title', ["keyword" => $keyword]) : trans('manage/lessons.title') }} <a href="{{ url('manage/lessons/create') }}"><i class="fa fa-plus-circle"></i></a>
                        <a href="javascript:;" id="search-button"><i class="fa fa-search"></i></a></h2>
                    <div class="row">
                        @if(count($lessons))
                            <table class="table table-striped table-responsive">
                                <thead>
                                <tr>
                                    <td>{{ trans('manage/lessons.table_header.title') }}</td>
                                    <td>{{ trans('manage/lessons.table_header.duration') }}</td>
                                    <td>{{ trans('manage/lessons.table_header.series') }}</td>
                                    <td>{{ trans('manage/lessons.table_header.tutor') }}</td>
                                    <td></td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($lessons as $lesson)
                                    <tr data-id="{{ $lesson->id }}">
                                        <td><a href="{{ action('ManageController@showEditLesson', ["id" => $lesson->id]) }}">{{ str_limit($lesson->title, 20) }}</a></td>
                                        <td>{{ $lesson->duration }}</td>
                                        <td>{{ str_limit($lesson->series->title, 20) }}</td>
                                        <td>{{ str_limit($lesson->user->display_name, 10) }}</td>
                                        <td><a class="btn btn-danger" href="javascript:;" id="delete-btn">{{ trans('manage/series.delete_button') }}</a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <h3 class="no-result">{{ trans('messages.no_result', ["name" => trans('lessons.lesson')]) }}</h3>
                        @endif
                    </div>
                </div>
            </aside>
        </section>
    </div>
@stop


@push('scripts.footer')
<script>
    $(function () {
        $("a#search-button").click(function () {
            swal({
                title: "{{ trans('manage/series.search_box_title') }}",
                text: "{{ trans('manage/series.search_box_message') }}:",
                type: "input",
                inputType: "search",
                showCancelButton: true,
                closeOnConfirm: true,
                cancelButtonText: "{{ trans('messages.cancel_button') }}",
                confirmButtonText: "{{ trans('messages.search_button') }}",
                animation: "slide-from-bottom",
            }, function (inputValue) {
                if (inputValue === false) return;
                if (inputValue === "") {
                    swal.showInputError("...");
                    return;
                }
                window.location.href = "{{ url('manage/lessons/search/') }}/" + inputValue;
            });
        });

        function deleteDidClick($id) {
            swal({
                title: "{{ trans('messages.are_you_sure_delete') }}",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "{{ trans('messages.cancel_button') }}",
                confirmButtonText: "{{ trans('messages.delete_button') }}",
                showLoaderOnConfirm: true,
                closeOnConfirm: false
            }, function () {
                $.ajax({
                    url: "{{ url('manage/lessons') }}/" + $id,
                    type: "DELETE",
                    data: {_token: "{{ csrf_token() }}"},
                    dataType: "json",
                    success: function (data) {
                        swal({title:data.message, type:data.status,timer: 1000,showConfirmButton: false});
                        var selector = 'tr[data-id="' + $id + '"]';
                        data.status == "success" ? $(selector).fadeOut(1000,function() {$(this).remove()}) : null;
                    }
                });
            });
        }

        $('a#delete-btn').each(function () {
            $(this).click(function () {
                deleteDidClick($($(this).parents('tr')[0]).attr('data-id'));
            });
        });
    });
</script>
@endpush
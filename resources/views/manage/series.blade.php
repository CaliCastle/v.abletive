@extends('layouts.app')

@section('title', isset($keyword) ? trans('manage/series.search-title', ["keyword" => $keyword]) : trans('manage/series.title'))

@section('content')
    <div class="container">
        <section class="setting flex-container">
            @include('manage.partials.nav')
            <aside class="box-right">
                <div class="content">
                    <h2 class="heading">{{ isset($keyword) ? trans('manage/series.search-title', ["keyword" => $keyword]) : trans('manage/series.title') }} <a href="{{ url('manage/series/create') }}"><i class="fa fa-plus-circle"></i></a>
                        <a href="javascript:;" id="search-button"><i class="fa fa-search"></i></a></h2>
                    <div class="row">
                        @if(count($series))
                            <table class="table table-striped table-responsive">
                                <thead>
                                <tr>
                                    <td>{{ trans('manage/series.table_header.title') }}</td>
                                    <td>{{ trans('manage/series.table_header.lessons') }}</td>
                                    <td>{{ trans('manage/series.table_header.completed') }}</td>
                                    <td></td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($series as $s)
                                    <tr data-id="{{ $s->id }}">
                                        <td><a class="title" href="{{ $s->link() }}" target="_blank">{{ str_limit($s->title, 35) }}</a></td>
                                        <td>{{ $s->lessons->count() }}</td>
                                        <td><span>{!! $s->completed ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>' !!}</span></td>
                                        <td><a class="btn btn-primary" href="{{ action('ManageController@showEditSeries', ["id" => $s->id]) }}">{{ trans('manage/series.edit_button') }}</a> <a class="btn btn-danger" id="delete-btn" href="javascript:;">{{ trans('manage/series.delete_button') }}</a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="row text-center">
                                <p>
                                    {!! $series->links() !!}
                                </p>
                            </div>
                        @else
                            <h3 class="no-result">{{ trans('messages.no_result', ["name" => trans('series.series-lessons')]) }}</h3>
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
                window.location.href = "{{ url('manage/series/search/') }}/" + inputValue;
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
                    url: "{{ url('manage/series') }}/" + $id,
                    type: "DELETE",
                    data: {_token: "{{ csrf_token() }}"},
                    dataType: "json",
                    success: function (data) {
                        swal({title:data.message, type:data.status,timer: 1000,showConfirmButton: false});
                        var selector = 'tr[data-id="' + $id + '"]';
                        $(selector).fadeOut(1000,function() {$(this).remove()});
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
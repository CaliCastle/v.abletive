@extends('layouts.app')

@section('title', trans('manage/series.edit_title'))

@push('styles')
<link rel="stylesheet" href="{{ url('css/select2.min.css') }}">
@endpush

@section('content')
    <div class="container">
        <section class="setting flex-container">
            @include('manage.partials.nav')
            <aside class="box-right">
                <div class="content">
                    <h2 class="heading">{{ trans('manage/series.edit_title') }}</h2>
                    @include('manage.series.partials.form',[
                        "url" => action('ManageController@updateSeries', ["id" => $series->id]),
                        "button_text" => trans('manage/series.update'),
                        "status" => "edit"])
                </div>
            </aside>
        </section>
    </div>
@stop

@push('scripts.footer')
<script src="{{ url('js/select2.min.js') }}"></script>
<script>
    $(function () {
        $('#skill-select').select2({

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

                        if (data.status == "success") {
                            setTimeout(function () {
                                window.location.href = "{{ url('manage/series') }}";
                            }, 800);
                        }
                    }
                });
            });
        }

        $('a#delete-btn').click(function () {
            deleteDidClick({{ $series->id }});
        });
    });
</script>
@endpush
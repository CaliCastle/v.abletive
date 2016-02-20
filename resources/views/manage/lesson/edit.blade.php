@extends('layouts.app')

@section('title', trans('manage/lessons.edit_title'))

@push('styles')
<link rel="stylesheet" href="{{ url('css/select2.min.css') }}">
@endpush

@section('content')
    <div class="container">
        <section class="setting flex-container">
            @include('manage.partials.nav')
            <aside class="box-right">
                <div class="content">
                    <h2 class="heading">{{ trans('manage/lessons.edit_title') }}</h2>
                    @include('manage.lesson.partials.form', [
                       "url" => action('ManageController@updateLesson', ["id" => $lesson->id]),
                       "button_text" => trans('manage/lessons.edit_button'),
                       "type" => "edit"])
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

                        setTimeout(function () {
                            if (data.status == "success") {
                                window.location.href = "{{ url('manage/lessons') }}";
                            }
                        }, 1000);
                    }
                });
            });
        }

        $('a#delete-btn').click(function () {
            deleteDidClick({{ $lesson->id }});
        });
    });
</script>
@endpush
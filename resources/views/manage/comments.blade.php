@extends('layouts.app')

@section('title', isset($keyword) ? trans('manage/series.search-title', ["keyword" => $keyword]) : trans('manage/comments.title'))

@section('content')
    <div class="container">
        <section class="setting flex-container">
            @include('manage.partials.nav')
            <aside class="box-right">
                <div class="content">
                    <h2 class="heading">{{ isset($keyword) ? trans('manage/series.search-title', ["keyword" => $keyword]) : trans('manage/comments.title') }} <a href="javascript:;" id="search-button"><i class="fa fa-search"></i></a></h2>
                    <div class="row">
                        @if(count($comments))
                            <table class="table table-responsive table-striped">
                                <thead>
                                <tr>
                                    <td>{{ trans('manage/comments.user') }}</td>
                                    <td>{{ trans('manage/comments.video') }}</td>
                                    <td>{{ trans('manage/comments.message') }}</td>
                                    <td></td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($comments as $comment)
                                    <tr data-id="{{ $comment->id }}">
                                        <td><a href="{{ $comment->user->profileLink() }}">{{ str_limit($comment->user->display_name, 15) }}</a></td>
                                        <td><a href="{{ $comment->lesson->link() }}">{{ str_limit($comment->lesson->title, 20) }}</a></td>
                                        <td>{{ str_limit($comment->message, 50) }}</td>
                                        <td><a href="javascript:;" class="btn btn-danger" id="delete-btn">{{ trans('manage/series.delete_button') }}</a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="row text-center">
                                <p>
                                    {!! $comments->links() !!}
                                </p>
                            </div>
                        @else
                            <h3 class="no-result">{{ trans('messages.no_result', ["name" => trans('discussion.comment')]) }}</h3>
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
                window.location.href = "{{ url('manage/comments/search/') }}/" + inputValue;
            });
        });

        $('a#delete-btn').each(function () {
            $(this).click(function () {
                var el = $(this);
                var $id = $($(this).parents('tr')[0]).attr('data-id');
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
                        url: "{{ url('manage/comments') }}/" + $id,
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
            });
        });
    });
</script>
@endpush
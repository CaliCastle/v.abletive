@extends('layouts.app')

@section('title', '问题管理')

@section('content')
    <div class="container">
        <section class="setting flex-container">
            @include('manage.partials.nav')
            <aside class="box-right">
                <div class="content">
                    <h2 class="heading">问题管理 <a href="{{ action('ManageController@showCreateQuestion', ["id" => $examination->id]) }}"><i class="fa fa-plus"></i></a></h2>
                    <div class="row">
                        <table class="table table-responsive table-striped">
                            <thead>
                            <tr>
                                <td>标题</td>
                                <td>操作</td>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($questions as $question)
                                <tr data-id="{{ $question->id }}">
                                    <td>{{ $question->title }}</td>
                                    <td>
                                        <a href="{{ action('ManageController@showEditQuestion', ["id" => $question->id]) }}" class="primary">编辑</a>
                                        <a href="javascript:;" style="color: red;" id="delete-btn">删除</a>
                                    </td>
                                </tr>
                            @empty
                                <h3 class="no-result">暂无相关问题</h3>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </aside>
        </section>
    </div>
@stop

@push('scripts.footer')
<script>
    $(function () {
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
                        url: "{{ url('manage/question') }}/" + $id,
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
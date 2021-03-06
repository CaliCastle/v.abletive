@extends('layouts.app')

@section('title', '考试管理')

@section('content')
    <div class="container">
        <section class="setting flex-container">
            @include('manage.partials.nav')
            <aside class="box-right">
                <div class="content">
                    <h2 class="heading">{{ trans('manage/sidebar.examinations') }} <a href="{{ url('manage/examination/create') }}"><i class="fa fa-plus"></i></a></h2>
                    <div class="row">
                        <table class="table table-responsive table-striped">
                            <thead>
                            <tr>
                                <td>标题</td>
                                <td>通过人数</td>
                                <td>操作</td>
                            </tr>
                            </thead>
                            <tbody>
                        @forelse($examinations as $examination)
                            <tr data-id="{{ $examination->id }}">
                                <td><a href="{{ action('ManageController@showExamQuestions', ["id" => $examination->id]) }}">{{ $examination->title }}</a></td>
                                <td>{{ $examination->passedUsers()->count() }}</td>
                                <td>
                                    <a href="{{ action('ManageController@showEditExamination', ["id" => $examination->id]) }}" class="primary">编辑</a>
                                    <a href="javascript:;" style="color: red;" id="delete-btn">删除</a>
                                </td>
                            </tr>
                        @empty
                            <h3 class="no-result">暂无考试</h3>
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
                        url: "{{ url('manage/examination') }}/" + $id,
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
@extends('layouts.app')

@section('title', trans('manage/users.title'))

@section('content')
    <div class="container">
        <section class="setting flex-container">
            @include('manage.partials.nav')
            <aside class="box-right">
                <div class="content">
                    <h2 class="heading">{{ trans('manage/users.title') }}</h2>
                    <div class="row">
                        @if(count($users))
                            <table class="table table-responsive table-striped">
                                <thead>
                                <tr>
                                    <td>{{ trans('manage/users.name') }}</td>
                                    <td>{{ trans('manage/users.email') }}</td>
                                    <td>{{ trans('manage/users.role') }}</td>
                                    <td></td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr data-id="{{ $user->id }}">
                                        <td><a href="{{ $user->profileLink() }}" target="_blank">{{ $user->display_name }}</a></td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->role }}</td>
                                        <td>
                                            @unless($user->id == auth()->user()->id)
                                            <a href="javascript:;" id="promote-btn">{{ $user->isTutor() || $user->isManager() ? trans('manage/users.unpromote') : trans('manage/users.promote') }}</a>
                                            @endunless
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <h3 class="no-result">{{ trans('messages.no_result', ["name" => trans('auth.user')]) }}</h3>
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
        $('a#promote-btn').each(function () {
            $(this).click(function () {
                var $id = $($(this).parents('tr')[0]).attr('data-id');
                swal({
                    title: "{{ trans('manage/users.promote-title') }}",
                    type: "warning",
                    showCancelButton: true,
                    showConfirmButton: true
                }, function () {
                    $.ajax({
                        url: "{{ url('manage/users/promote/') }}/" + $id,
                        data: {_token: "{{ csrf_token() }}"},
                        type: "PUT",
                        success: function (data) {
                            swal({title:data.message, timer: 1000, type: data.status, showConfirmButton: false});
                        }
                    });
                });
            });
        });
    });
</script>
@endpush
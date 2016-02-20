@extends('layouts.app')

@section('title', trans('profile.laters_title'))

@section('content')
    <div class="container">
        <section class="timeline">
            <div class="row">
                <h2 class="big-heading">
                    <span class="subtitle">{{ trans('profile.laters_subtitle') }}</span>
                    <span>{{ trans('profile.laters_title') }}</span>
                </h2>
            </div>
            <div class="row">
                <div class="col-lg-10 col-lg-offset-1">
                    @include('series.partials.lesson-special-list')
                </div>
            </div>
        </section>
    </div>
@stop

@push('scripts.footer')
<script>
    $(function () {
        var $_token = "{{ csrf_token() }}";
        // Lesson watch later
        $('a#watch-later-btn-icon').each(function () {
            var el = $(this);
            $(this).click(function () {
                $id = $($(this).parents('li')[0]).attr('video-id');
                $.ajax({
                    url: "{{ url('lessons/watch_later') }}/" + $id,
                    type: "POST",
                    data: {_token: $_token},
                    dataType: "json",
                    success: function (data) {
                        el.toggleClass('active');
                        swal({title: data.message, type: "success", timer: 1500, showConfirmButton: false});
                    }
                });
            });
        });
    });
</script>
@endpush
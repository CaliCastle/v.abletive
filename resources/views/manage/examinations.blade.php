@extends('layouts.app')

@section('title', '考试管理')

@section('content')
    <div class="container">
        <section class="setting flex-container">
            @include('manage.partials.nav')
            <aside class="box-right">
                <div class="content">
                    <h2 class="heading">{{ trans('manage/sidebar.examinations') }} <a href="{{ url('manage/examination/add') }}"><i class="fa fa-plus"></i></a></h2>
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
                                <td>{{ $examination->title }}</td>
                                <td>{{ $examination->passedUsers()->count() }}</td>
                                <td></td>
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
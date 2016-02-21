@extends('layouts.app')

@section('title', trans('app/site.pages.join'))

@section('header-content')
    <div class="header-content">
        <div class="container">
            <div class="row section">
                <div class="text-center">
                    <h1 class="heading animated animated-delay1 fadeInUp">{{ trans('app/site.pages.join') }}</h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <section class="main-content">
        <div class="container">
            <div class="row section">
                <h2 class="big-heading">
                    <span class="subtitle">赶快成为讲师给大家传授不一样的知识</span>
                    <span>申请讲师</span>
                </h2>
            </div>
            <div class="row text-center">
                <h3>申请发送至邮箱: cali@calicastle.com</h3>
                <h3>邮件标题: 【申请讲师】</h3>
                <h3>邮件格式:</h3>
                <br>
                <h2>1.申请讲授内容方向<br><br>2.作品视频链接（若没有请说明）<br><br>3.昵称与社区ID（个人主页链接）</h2>
            </div>
        </div>
    </section>
@stop
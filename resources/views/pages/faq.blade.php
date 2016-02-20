@extends('layouts.app')

@section('title', trans('app/site.pages.faq'))

@section('header-content')
    <div class="header-content">
        <div class="container">
            <div class="row section">
                <div class="text-center">
                    <h1 class="heading animated animated-delay1 fadeInUp">{{ trans('app/site.pages.faq') }}</h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="main-content">
    <div class="container">
        <div class="section">
            <div class="row">
                <h2 class="big-heading"><span class="subtitle">网站的教程费用?</span><span>教学视频都是免费的吗?</span></h2>
            </div>
            <div class="row text-center">
                <h3>只有个别的系列需要社区会员才能观看, 大部分都是免费公开的, 入门掌握基础肯定可以的, 尽可放心</h3>
                <h3>我当然也希望所有视频都是免费的, 不过我投入了大量的时间与精力从网站建设到教程录制.</h3>
                <h3>所以你的小小资金支持能给予我继续录更好的视频动力</h3>
            </div>
            <div class="row">
                <h2 class="big-heading"><span>如何成为网站会员?</span></h2>
            </div>
            <div class="row text-center">
                <h3>前往<a href="http://abletive.com" target="_blank">社区页面</a>登录后进入个人主页即可充值会员</h3>
            </div>
            <div class="row">
                <h2 class="big-heading"><span>会员收费多少?</span></h2>
            </div>
            <div class="row text-center">
                <h3>月费9/￥, 季费25/￥, 年费90/￥, 终身300/￥</h3>
                <h3>(在社区 <a href="https://livemax.taobao.com" target="_blank">淘宝旗舰店</a> 购买控制器免费送一年会员噢)</h3>
            </div>
            <div class="row">
                <h2 class="big-heading"><span>会员还有什么好处?</span></h2>
            </div>
            <div class="row text-center">
                <h3>会员还可以在 <a href="http://vip.abletive.com" target="_blank">会员专属页面</a> 中下载分类好的工程,视频与教程等.</h3>
                <h3>更多好处在将来也会有更多体现</h3>
            </div>
            <div class="row">
                <h2 class="big-heading"><span class="subtitle">Launchpad没声音?</span><span>没声音</span></h2>
            </div>
            <div class="row text-center">
                <h3>Launchpad自身是不会发出任何声音的, 请检查软件设置. </h3>
            </div>
            <div class="row">
                <h2 class="big-heading"><span class="subtitle">Launchpad没灯光?</span><span>没灯光</span></h2>
            </div>
            <div class="row text-center">
                <h3>Launchpad RGB/Pro 灯光轨的I/O调整输出为Ch.3/6</h3>
                <h3>Launchpad S/Mini 灯光轨的I/O调整输出为Ch.1</h3>
            </div>
        </div>
    </div>
</div>
@stop
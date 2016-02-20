@extends('layouts.app')

@section('title', trans('app/site.pages.about'))

@section('header-content')
    <div class="header-content">
        <div class="container">
            <div class="row section">
                <div class="text-center">
                    <h1 class="heading animated animated-delay1 fadeInUp">{{ trans('app/site.pages.about') }}</h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <section class="main-content">
        <div class="container">
            <div class="section">
                <div class="row">
                    <h2 class="big-heading"><span class="subtitle">Abletive社区大家庭</span><span>音乐社区</span></h2>
                </div>
                <div class="row text-center section about">
                    <p><img src="{{ url('favicon.png') }}" alt=""></p>
                    <h3>恭喜社区成立了{{ \Carbon\Carbon::parse("2014-12-10")->diffInMonths() }}个月，于2014-12-10正式成立，2015-02-06网站访问量达到了10万，2015-02-28更新了v2.0，2015-04-20网站访问量达到了100万，到了2015-07-21已积攒1300万的访问量，服务器进行了几次升级迁移，用户增长到了1.3万。截至2015年12月10日社区成立一周年，网站用户已达到2万，非常感谢大家的支持。</h3>
                    <h3>我们是一个大家庭</h3>
                    <h3>在这里 你会找到独一无二详细的教程和收藏已久的资源等分享</h3>
                    <h3>我们不只分享Launchpad工程</h3>
                    <h3>我们分享免费的Launchpad工程，素材资源，歌曲下载等等一些能够帮助大家不用到处搜寻就可以下载到的东西</h3>
                    <h3>社区网站(<a href="http://abletive.com">http://abletive.com</a>)由Cali独立开发完成，更新主题多次, 后台框架为WordPress</h3>
                    <br>
                    <h3>视频教学站(<a href="{{ url('') }}">{{ url('') }}</a>)也由Cali独立开发完成, 框架为Laravel.</h3>
                    <br>
                </div>
            </div>
        </div>
    </section>
@stop
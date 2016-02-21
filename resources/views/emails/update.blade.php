<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>《{{ $series->title }}》有更新</title>
</head>
<style type="text/css">
    body {
        background: #F7F7F7;
        font-family: "PingFang SC", "Microsoft Yahei", "微软雅黑", Helvetica Neue, Helvetica, Arial;
    }

    .container {
        width: 600px;
        left: 50%;
        position: relative;
        margin-left: -300px;
        margin-top: 50px;
    }

    h1 {
        color: #000;
    }

    .container > img {
        width: 110px;
        margin-left: -55px;
        left: 50%;
        position: relative;
    }

    .box {
        padding: 25px;
        margin: 10px 0;
        background-color: #fff;
    }

    .box h3 {
        text-align: center;
        line-height: 30px;
        font-size: 30px;
        background-color: #573e81;
        padding: 10px;
        color: #fff;
    }
    
    .box b {
        text-align: center;
        padding: 8px;
    }

    .box p {
        line-height: 28px;
    }
    
    .box img {
        max-width: 75%;
    }

    .footer {
        text-align: right;
    }
    
    .footer a {
        color: #aaa;
        font-size: 16px;
        font-weight: bold;
    }
</style>
<body>
<div class="container">
    <img src="{{ url('favicon.png') }}" alt="">
    <div class="box">
        <h3>系列课程更新啦!</h3>
        <b><a href="{{ $series->link() }}" target="_blank"><img src="{{ $series->thumbnail }}" alt="{{ $series->title }}"></a></b>
        <p>嗨, <strong>{{ $user->display_name }}</strong>! - </p>
        <strong>您所订阅的《<strong><a href="{{ $series->link() }}" target="_blank">{{ $series->title }}</a></strong>》现在更新了课程噢!</strong>
        <h3 style="text-align:center;"><a href="{{ $lesson->link() }}" target="_blank" style="color: #eee; text-decoration: none;">{{ $lesson->episode() }}.{{ $lesson->title }}</a></h3>
        <h4>课程介绍: {!! $lesson->description !!}</h4>
        <h4><a href="{{ url('settings/notification') }}">随时取消订阅</a></h4>
        <p>Cali Castle (@abletive)</p>
    </div>
    <div class="footer">
        <p>Abletive视频教学站</p>
        <a href="http://v.abletive.com">http://v.abletive.com</a>
        <br>
    </div>
</div>
</body>
</html>

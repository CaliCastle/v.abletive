<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>{{ $sender->display_name . "在《{$lesson->title}》中回复了您:" }}</title>
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

    .container > p > img {
        width: 120px;
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
        padding: 10px;
        color: #111;
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

    .avatar {
        display: inline-block;
        width: 60px;
        border-radius: 50%;
        vertical-align: middle;
        margin-right: 10px;
    }

    strong {
        display: block;
        line-height: 100px;
        padding: 20px;
    }
</style>
<body>
<div class="container">
    <p style="text-align:center;"><img src="{{ url('favicon.png') }}" alt=""></p>
    <div class="box">
        <h2 style="text-align:center;">{{ $sender->display_name . "在《{$lesson->title}》中回复了您:" }}</h2>
        <strong><img class="avatar" src="{{ $sender->avatar }}" alt="{{ $sender->display_name }}的头像">:{!! $content !!}</strong>
        <p>点击下面的链接前往查看:</p>
        <h3 style="text-align:center;"><a href="{{ $lesson->link() }}" target="_blank" style="color: #222; text-decoration: none;">{{ $lesson->episode() }}.{{ $lesson->title }}</a></h3>
        <b><a href="{{ $lesson->series->link() }}" target="_blank"><img src="{{ $lesson->series->thumbnail }}" alt="{{ $lesson->series->title }}"></a></b>
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

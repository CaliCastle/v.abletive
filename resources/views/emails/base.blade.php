<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
</head>
<style type="text/css">
    body {
        background: #F7F7F7;
        font-family: "PingFang SC", "Microsoft Yahei", "微软雅黑", Helvetica Neue, Helvetica, Arial;
    }

    .container {
        justify-content: center;
        align-items: center;
        position: relative;
        display: flex;
        margin-top: 150px;
    }

    h1 {
        color: #000;
    }

    .container > img {
        width: 110px;
        margin-left: -55px;
        left: 50%;
        position: absolute;
        top: -120px;
    }

    .box {
        padding: 25px;
        margin: 10px 0;
        background-color: #fff;
        flex: 1;
        max-width: 500px;
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
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .box p {
        line-height: 28px;
    }

    .box img {
        max-width: 75%;
        flex: 1;
    }

    .footer {
        text-align: right;
        position: absolute;
        bottom: -10%;
        right: 5%;
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

    .container > strong {
        display: block;
        line-height: 100px;
        padding: 20px;
    }
</style>
<body>
<div class="container">
    <img src="{{ url('favicon.png') }}" alt="Abletive视频教学网">
    <div class="box">
        @yield('content')
        <h4><a href="{{ url('settings/notification') }}">随时取消订阅</a></h4>
        <p>祝您学习旅途愉快~</p>
        <p>Cali Castle (@abletive)</p>
    </div>
    <div class="footer">
        <p>Abletive视频教学站</p>
        <a href="{{ url('/') }}">{{ url('/') }}</a>
        <br>
    </div>
</div>
</body>
</html>
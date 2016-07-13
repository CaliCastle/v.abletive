@extends('layouts.app')

@section('title', trans('app/site.pages.contact'))

@section('header-content')
    <div class="header-content">
        <div class="container">
            <div class="row section">
                <div class="text-center">
                    <h1 class="heading animated animated-delay1 fadeInUp">{{ trans('app/site.pages.contact') }}</h1>
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
                <h2 class="big-heading"><span class="subtitle">加入QQ群</span><span>官方QQ群</span></h2>
            </div>
            <div class="row qq-qrcode">
                <div class="col-lg-6"><img src="{{ url('assets/images/qq2_qrcode.JPG') }}" alt=""></div>
                <div class="col-lg-6"><img src="{{ url('assets/images/qq_qrcode2.JPG') }}" alt=""></div>
            </div>
            <div class="row">
                <h2 class="big-heading"><span class="subtitle">联系站长</span><span>站长Cali</span></h2>
            </div>
            <div class="row text-center">
                <h3>QQ: 739805509</h3>
                <h3>邮箱: cali@calicastle.com</h3>
            </div>
        </div>
    </div>
</section>
@stop
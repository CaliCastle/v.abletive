@if(auth()->guest())
    <div id="login-dialog" class="dialog">
        <div class="dialog__overlay"></div>
        <div class="dialog__blur"></div>
        <div class="dialog__content">
            <h2 class="animated fadeInUp">{!! trans('auth.login_title', ["login" => trans('auth.login'), "title" => trans('app/site.title')]) !!}</h2>
            <form id="login-form" action="{{ url('login') }}" method="POST" class="form-horizontal col-lg-12">
                {!! csrf_field() !!}
                <input type="checkbox" name="remember" hidden checked>
                <div class="form-group animated fadeInUp">
                    <input type="text" name="name" class="form-control col-lg-10" placeholder="{{ trans('auth.input.name') }}..." required>
                </div>
                <div class="form-group animated fadeInUp">
                    <input type="password" name="password" class="form-control col-lg-10" placeholder="{{ trans('auth.input.password') }}..." required>
                </div>
                <div class="form-group animated fadeInUp">
                    <input type="submit" class="btn btn-block btn-primary" value="{{ trans('auth.input.submit') }}">
                </div>
            </form>
        </div>
    </div>
@endif
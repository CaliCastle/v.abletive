@push('scripts.footer')
<script>
    function showStatusMessage(message) {
        // create the notification
        var notification = new NotificationFx({
            message : '<span class="icon fa fa-bullhorn"></span><p class="animated animated-delay1 rubberBand">' + message + '</p>',
            layout : 'attached',
            effect : 'bouncyflip',
            type : 'notice', // notice, warning or error,
            ttl : 5000 ,
            onClose : function() {

            }
        });
        // show the notification
        notification.show();
    }

    function showGenieMessage(message) {
        // create the notification
        var notification = new NotificationFx({
            message : '<p class="animated animated-delay1 rubberBand">' + message + '</p>',
            layout : 'growl',
            effect : 'genie',
            type : 'notice', // notice, warning or error,
            ttl : 5000 ,
            onClose : function() {

            }
        });
        // show the notification
        notification.show();
    }

    function showCookieMessage(message) {
        // create the notification
        var notification = new NotificationFx({
            message : '<p class="animated animated-delay7 bounceInDown">' + message + '</p>',
            layout : 'bar',
            effect : 'exploader',
            type : 'notice', // notice, warning or error,
            ttl : 9000000 ,
            onClose : function() {

            }
        });
        // show the notification
        notification.show();
    }

    (function () {
        @if(isset($errors))
            @if($errors->first())
            showStatusMessage("{{ $errors->first() }}")
            @endif
        @endif
        @if(session('status'))
        showStatusMessage("{!! session('status') !!}");
        @endif
        @if(session('notification'))
        showGenieMessage("{!! session('notification') !!}");
        @endif
        @unless(request()->hasCookie('allows_cookie'))
        showCookieMessage("{!! trans('app/site.cookie') !!}");

        $('a#cookie-button').on('click', function () {
            $.ajax({
                type: "POST",
                url: "{{ url('allows_cookie') }}",
                data: {_token: "{{ csrf_token() }}"},
                dataType: 'text',
                success: function (data) {
                    if (data == "Allowed") {
                        $(".ns-bar.ns-effect-exploader").fadeOut(500);
                        showGenieMessage("{!! trans('app/site.allowed_cookie')  !!}");
                    }
                }
            })
        });
        @endunless
    })();
</script>
@endpush
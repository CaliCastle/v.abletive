<section class="related" id="related">
    <div class="container">
        <div class="col-lg-4 animated animated-delay1 fadeInLeft">
            <div class="row">
                <h3 class="title">{{ trans('app/site.title') }}</h3>
            </div>
            <div class="row">
                <p class="description">{{ trans('app/site.description') }}</p>
            </div>
            <div class="row">
                <ul class="list-unstyled list-inline related-links">
                    <li><a href="http://wechat.abletive.com" target="_blank"><i class="fa fa-wechat"></i></a></li>
                    <li><a href="http://jq.qq.com/?_wv=1027&k=2FQDmwP" target="_blank"><i class="fa fa-qq"></i></a></li>
                    <li><a href="http://weibo.com/abletive" target="_blank"><i class="fa fa-weibo"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="col-lg-12 site-links">
                <div class="col-sm-4 animated animated-delay5 fadeInUp">
                    <span class="heading">{{ trans('app/site.footer.learn') }}</span>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('lessons') }}">{{ trans('header/navbar.library') }}</a></li>
                        <li><a href="{{ url('series') }}">{{ trans('header/navbar.library_items.series') }}</a></li>
                        <li><a href="{{ url('tags') }}">{{ trans('header/navbar.library_items.tags') }}</a></li>
                    </ul>
                </div>
                <div class="col-sm-4 animated animated-delay7 fadeInUp">
                    <span class="heading">{{ trans('app/site.footer.pages') }}</span>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('faq') }}">{{ trans('app/site.pages.faq') }}</a></li>
                        <li><a href="{{ url('about') }}">{{ trans('app/site.pages.about') }}</a></li>
                        <li><a href="{{ url('testimonials') }}">{{ trans('app/site.pages.testimonials') }}</a></li>
                        <li><a href="{{ url('join') }}">{{ trans('app/site.pages.join') }}</a></li>
                        <li><a href="{{ url('contact') }}">{{ trans('app/site.pages.contact') }}</a></li>
                    </ul>
                </div>
                <div class="col-sm-4 animated animated-delay9 fadeInUp">
                    <span class="heading">{{ trans('app/site.footer.related') }}</span>
                    <ul class="list-unstyled">
                        <li><a href="http://abletive.com" target="_blank">{{ trans('app/site.related_links.abletive') }}</a></li>
                        <li><a href="http://vip.abletive.com" target="_blank">{{ trans('app/site.related_links.vip_abletive') }}</a></li>
                        <li><a href="http://chat.abletive.com" target="_blank">{{ trans('app/site.related_links.chat_abletive') }}</a></li>
                        <li><a href="http://bbs.abletive.com" target="_blank">{{ trans('app/site.related_links.bbs_abletive') }}</a></li>
                        <li><a href="http://lp.abletive.com" target="_blank">{{ trans('app/site.related_links.lp_abletive') }}</a></li>
                        <li><a href="http://2016.abletive.com" target="_blank">{{ trans('app/site.related_links.2016_abletive') }}</a></li>
                        <li><a href="http://en.abletive.com" target="_blank">{{ trans('app/site.related_links.en_abletive') }}</a></li>
                        <li><a href="http://www.calicastle.com" target="_blank">{{ trans('app/site.related_links.calicastle') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<footer class="main-footer">
    <div class="container">
        <p class="copyright animated animated-delay2 fadeInUp">
            &copy; Abletive {{ date('Y') }}. {{ trans('app/site.copyright') }}
        </p>
        <p class="developer animated animated-delay5 fadeInUp">
            {!! trans('app/site.developer') !!}
        </p>
    </div>
</footer>
<div class="utilities">
    <div id="back-to-top" class="box-hide fadeIn">
        <i class="fa fa-chevron-up"></i>
    </div>
    <div id="back-to-bottom" class="animated fadeIn">
        <i class="fa fa-chevron-down"></i>
    </div>
</div>
@push('scripts.footer')
<script>
    jQuery(document).ready(function(){
        $body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');

        var $top = $('#back-to-top'),
            $bottom = $('#back-to-bottom');

        // Scroll to top
        $top.click(function(){
            $body.animate({
                scrollTop: $body.offset().top
            }, 1000);
            return false;
        });
        // Scroll to bottom
        $bottom.click(function(){
            $body.animate({
                scrollTop: $body.height()
            }, 1000);
            return false;
        });
    });

    jQuery(window).scroll(function() {
        var $top = $($('#back-to-top')[0]),
            $bottom = $($('#back-to-bottom')[0]);

        if ($(this).scrollTop() <= $('header').height()) {
            $top.addClass('box-hide');
        } else {
            $top.removeClass('box-hide');
        }

        if ($(this).scrollTop() + $(this).height() > $($('#related')[0]).offset().top) {
            $bottom.addClass('box-hide');
        } else {
            $bottom.removeClass('box-hide');
        }
    });
</script>
@endpush
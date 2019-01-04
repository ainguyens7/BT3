<!DOCTYPE html>
<html>
<head>
    @include('layout.head')
    {{--extend style--}}
    @yield('styles')
    
    @if(env('APP_ENV') == 'production' && empty($non_google_analytics))
    <!-- Global site tag (gtag.js) - Google Analytics 20180528 -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-105008699-3"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'UA-105008699-3');
    </script>
    @endif

    <!-- Intercom widget -->
    @php
        $shopRepo = \App\Factory\RepositoryFactory::shopsReposity();
        $find = $shopRepo->detail(['shop_id' => session('shopId')]);
        $shopInfo = $find['status'] ? $find['shopInfo'] : NULL;
        $intercom = new \App\Services\IntercomService(session('shopId'));
    @endphp

    @if (isset($shopInfo))
        <script>
            window.intercomSettings = {
                app_id: "pqt7dlbm",
                name: "{{$shopInfo->shop_owner}}",
                email: "{{$shopInfo->shop_email}}",
                created_at: {{$shopInfo->created_at->timestamp}},
                installed_at: {{ $shopInfo->created_at->timestamp }},
                plan_name: "{{$shopInfo->app_plan}}",
                shop_domain: "{{$shopInfo->shop_name}}",
                app_name: "alireviews",
                ali_reviews: true,

                imported_reviews: {{$intercom->getTotalReviews()}},
                reviewed_products: {{$intercom->getTotalReviewedProducts()}},

                upgraded_at: {{$intercom->getShopUpgradedAt()}},
                downgraded_at: {{$intercom->getShopDowngradedAt()}},
                uninstalled_at: {{$intercom->getShopUninstalledAt()}},

                review_imported: {{$intercom->checkReviewImported() ? 'true' : 'false'}},
                keyword_filter: {{$intercom->checkKeywordFilter() ? 'true' : 'false'}},
                review_page: {{$intercom->checkReviewPage() ? 'true' : 'false'}}
            };
        </script>

        <script>
            (function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/pqt7dlbm';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()
            document.addEventListener("DOMContentLoaded", function(event) {
                var isIntercom = false
                document.getElementById('toggleBeacon').addEventListener('click', function () {
                    if (isIntercom) {
                        Intercom('hide');
                        isIntercom = false;
                    } else {
                        Intercom('show');
                        isIntercom = true;
                    }
                });
            });
        </script>
    @endif
</head>

<body class="{{ isset($no_search) ? 'page-fullheight' : '' }} {{ isset($dashboardPage) ? 'dashboard-page' : '' }} @yield('body-class')">

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-M998TMH"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

@yield('codeTracking')
<div class="wrapper">
    <!--Logo - breadcrumb and shop_url-->
    @yield('header')

    <div class="body-wrap">

        @include('layout.sidebar')

        @yield('dashboard_statics')

        <div class="content-wrap">
            <div class="sidbar-mrk text-center">
                @if($showFreeUser)
                <!-- Show POPUP 1 -->
                    <button class="btn" data-toggle="modal" data-target="#popup-1">Remove Branding Logo</button>
                <!-- Show POPUP 1 -->
                @endif
            </div>

            <div class="content-container">
                @include('sections.localizationjs')
                @yield('container_content')
            </div>
            <div class="copyright-wrap">
                <p>Copyright {{ date('Y') }} <a href="https://fireapps.io" target="_blank">FireApps</a>. <span>All rights reserved.</span>
                </p>
            </div>
        </div>
        {{--<div class="support-btn-wrap">
            <a class="support-btn" href="javascript:void(0)" data-action="show"><img
                        src="{{ URL::asset('images/support-icon.png?v2') }}" alt="">Hi! do you need help?</a>

            <div class="fb-page" style="display: none"
                 data-href="https://www.facebook.com/fireapps.io/"
                 data-tabs="messages"
                 data-width="400"
                 data-height="300"
                 data-small-header="true">
                <div class="fb-xfbml-parse-ignore">
                    <blockquote></blockquote>
                </div>
            </div>
        </div>--}}
    </div>
</div>
<div class="nav-backdrop"></div>

@include('layout.footer')
{{--Extend scripts--}}
@yield('modals')
@yield('scripts')

</body>
</html>

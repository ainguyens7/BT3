
<header id="header">
    <div class="logo">
        <span class="nav-hamburger"><i class="demo-icon  icon-menu"></i></span>
        <a class="hidden-xs" href="{{ route('apps.dashboard') }}"><img src="{{ URL::asset('images/backend/alireviews-logo.png') }}" alt="Ali Reviews"></a>
        <a class="hidden-sm hidden-lg hidden-md" href="{{ route('apps.dashboard') }}"><img src="{{ URL::asset('images/backend/alireviews-logo-mobile.png') }}" alt="Ali Reviews"></a>
    </div>
    <nav class="header-top">
        <div class="breadcrumb-wrap">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('apps.dashboard') }}"> <i class="demo-icon icon-home-1"></i>Dashboard</a></li>

                @if(!empty($page_title))
                    <li class="breadcrumb-item">{{ isset($page_title) ? $page_title : '' }}</li>
                @endIf
            </ol>
        </div>
        @if(session('shopDomain'))
            <div class="shopify-name-wrap">
                @php
                    $shopRepo = \App\Factory\RepositoryFactory::shopsReposity();
                    $shopPlanInfo = $shopRepo->shopPlansInfo(session('shopId'));
                @endphp

                @if(!empty($shopPlanInfo['planInfo']) &&  $shopPlanInfo['planInfo']['name'] =='free')
                    <span class="notify-upgrade"><i class="demo-icon icon-warning"></i>You’re using FREE version.<a href="{{ route('apps.upgrade') }}">Get More with PRO</a></span>
                @endif

                @if(!empty($shopPlanInfo['planInfo']) &&  $shopPlanInfo['planInfo']['name'] =='premium')
                    <span class="notify-upgrade"><i class="demo-icon icon-warning"></i> You're using PRO version. <a href="{{ route('apps.upgrade') }}">Get more with UNLIMITED</a></span>
                @endif
                <ul class="shopify-name">
                    {{--<li>
                        <a class="review-app-btn hidden-xs" target="_blank" title="Review app" href="https://apps.shopify.com/ali-reviews?reveal_new_review=true">
                            <img src="{{ URL::asset('images/backend/add-review-app-icon.png') }}" alt="">
                        </a>
                    </li>--}}
                    <li>
                        <a class="more-app-btn">
                            <img src="{{ URL::asset('images/backend/more-app-icon.png?v2') }}" alt="">
                        </a>
                        <div class="more-app-wrap">
                            <ul class="more-app-list">
                                {{--<li>
                                    <a href="">
                                        <span class="more-app-icon"><img src="{{ URL::asset('images/backend/alicashback-icon.png') }}" alt=""></span>
                                        <h3>Ali Cachback</h3>
                                        <p>Evaluate your experience</p>
                                    </a>
                                </li>--}}
                                {{--<li>
                                    <a href="">
                                        <span class="more-app-icon"><img src="{{ URL::asset('images/backend/upsell-icon.png') }}" alt=""></span>
                                        <h3>In Cart Upsell</h3>
                                        <p>Evaluate your experience</p>
                                    </a>
                                </li>--}}
                                <li>
                                    <a href="https://apps.shopify.com/shipping-information-by-fireapps" target="_blank">
                                        <span class="more-app-icon"><img src="{{ URL::asset('images/backend/shipping-info-icon.png') }}" alt=""></span>
                                        <h3>Shipping Information</h3>
                                        <p>Boost conversion & decrease cart abandonment</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://apps.shopify.com/real-time-visitor" target="_blank">
                                        <span class="more-app-icon"><img src="{{ URL::asset('images/backend/realtime-visitor-icon.png') }}" alt=""></span>
                                        <h3>Realtime Visitor</h3>
                                        <p>Turn your visitors into buyers</p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <img src="{{ URL::asset('images/backend/avatar-default.png') }}" alt="" width="25px">
                        <span>{{ session('shopDomain','') }}</span>
                        <div class="shopify-name-dropdown">
                            <ul class="shopify-name-link">
                                {{--<li><a class="select-shop-link" href=""><i></i>Select other shops</a></li>--}}
                                <li><a class="go-store-link" href="https://{{ session('shopDomain', '') }}" target="_blank"><i></i>Go to store</a></li>
                                <li><a href="https://{{ session('shopDomain', '') }}/admin/" target="_blank"><i class="icon-reply-all"></i>Back to Shopify</a></li>
                                <li><a href="{{ route('apps.installApp') }}" ><i class="demo-icon icon-power"></i>Logout</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        @endif
    </nav>
</header>
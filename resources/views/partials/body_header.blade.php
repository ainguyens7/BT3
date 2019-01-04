<nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a class="sidebar-toggle hidden-md hidden-lg" data-toggle="push-menu" role="button">
        <span class="icon-ic-menu-24px"></span>
    </a>

    <ul class="nav navbar-nav navigation hidden-xs hidden-sm p-absolute">
        <li>
            <a href="{{ route('apps.dashboard') }}"><span class=""></span> Ali Reviews</a>
        </li>
        @if (isset($breadcrumb_link))
            <li>
                <a><span>{{ $breadcrumb_link }}</span></a>
            </li>
        @endif
    </ul>

    @if (session('shopDomain'))
        @php
            $shopRepo = \App\Factory\RepositoryFactory::shopsReposity();
            $shopPlanInfo = $shopRepo->shopPlansInfo(session('shopId'));
        @endphp

    <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="vote-for-us btn-vote-for-us">
                    <img src="{{ cdn('images/vote-for-us.png') }}">
                </li>

                @if(!empty($shopPlanInfo['planInfo']) &&  $shopPlanInfo['planInfo']['name'] =='free')
                    <li class="hidden visible-lg-block wrap-unlimited">
                        <a href="{{ route('apps.upgrade') }}" class="btn btn-primary fw600">
                            You’re using
                            <span class="color-pink">FREE</span> plan. Get more with
                            <span class="color-pink">PRO</span>
                        </a>
                    </li>
                @endif

                @if(!empty($shopPlanInfo['planInfo']) &&  $shopPlanInfo['planInfo']['name'] =='premium')
                    <li class="hidden visible-lg-block wrap-unlimited">
                        <a href="{{ route('apps.upgrade') }}" class="btn btn-primary fw600">
                            You’re using
                            <span class="color-pink">PRO</span> plan. Get more with
                            <span class="color-pink">UNLIMITED</span>
                        </a>
                    </li>
                @endif

                <li class="dropdown linking-app">
                    <div class="dropdown-toggle" data-toggle="dropdown">
                        <i class="alimn alimn-linking-app"></i>
                    </div>
    
                    <ul class="dropdown-menu">
                        <li>
                            <a href="https://aliorders.fireapps.io" class="item" target="_blank">
                                <img src="{{ cdn('images/logo/aliorders-small.png') }}" alt="">
                                <div class="item-content">
                                    <strong>Ali Orders</strong>
                                    <p>Automate your dropshipping</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="https://salesbox.fireapps.io" class="item" target="_blank">
                                <img src="{{ cdn('images/logo/sales-box-small.png') }}" alt="">
                                <div class="item-content">
                                    <strong>Sales Box</strong>
                                    <p>More conversions with sales promotions</p>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- User Account: style can be found in -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle">
                        <span class="hidden-xs ali-icon-down">{{ session('shopDomain', '') }}</span>
                        <div class="user-image">
                            <img src="{{ cdn('images/avatar-default.png') }}" class="img-circle" alt="John Doe">
                        </div>
                    </a>

                    <ul class="dropdown-menu">
                        <li>
                            <a href="https://{{ session('shopDomain', '') }}" target="_blank">Go to store</a>
                        </li>
                        <li>
                            <a href="https://{{ session('shopDomain', '') }}/admin/" target="_blank">Back to Shopify</a>
                        </li>
                        <li role="separator" class="divider"></li>
                        <li>
                            <a href="{{ route('apps.installApp') }}">Logout</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    @endif
</nav>
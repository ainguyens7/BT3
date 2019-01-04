<!DOCTYPE html>
<html>
<head>
    @include('includes/head')
    @yield('head_script')
</head>

<body class="navside-fixed">
    @include('third-party/gtag_manager')
    <div class="wrapper">
        <header class="main-header">
            <a href="{{ route('apps.dashboard') }}" class="logo">
                <span class="logo-lg">
                    <img src="{{ cdn('images/alireviews-logo.png') }}" alt="Ali Reviews">
                </span>
            </a>
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <a class="sidebar-toggle hidden-md hidden-lg" data-toggle="push-menu" role="button">
                    <i class="material-icons">menu</i>
                </a>

                <ul class="nav navbar-nav navigation hidden-xs hidden-sm p-absolute">
                    <li>
                        <a href="{{ route('apps.dashboard') }}"><i class="material-icons">home</i> Ali Reviews</a>
                    </li>
                    @yield('breadcrumb')
                </ul>

                @include('includes/account')
            </nav>
        </header>

        <!-- SIDEBAR -->
        @include('includes/sidebar')
        <!-- END: SIDEBAR -->

        <!-- CONTENT WRAPPER -->
        <div class="wrapper-content">
            <div class="clearfix"></div>
            @yield('body_content')

            @if (session('shopDomain'))
                @php
                    $shopRepo = \App\Factory\RepositoryFactory::shopsReposity();
                    $shopPlanInfo = $shopRepo->shopPlansInfo(session('shopId'));
                @endphp
                @if(URL::current() !== URL::to('/install') && !empty($shopPlanInfo['planInfo']) &&  $shopPlanInfo['planInfo']['name'] == 'free')
                    @include('includes.converting_user')
                    @include('modal-mkt-6-popup.modal-converting-user')
                @endif
            @endif

            <footer class="footer">
                Copyright &copy;{{date('Y')}} <a href="https://fireapps.io/" target="blank"><strong class="color-pink">FireApps</strong></a>. All rights reservend
            </footer>
        </div>
    </div>
    
    @include('sections.localizationjs')
    @include('includes/footer_script')
    @include('dashboard.modal_vote')
    @include('dashboard.modal_redirect_to_aliorders')

    @yield('body_script')
</body>

</html>
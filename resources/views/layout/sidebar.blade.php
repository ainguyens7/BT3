@php
    $currentRouteName = \Illuminate\Support\Facades\Request::route()->getName();
    $commentRepo = \App\Factory\RepositoryFactory::commentBackEndRepository();
    $listReviewApprove = $commentRepo->all( session('shopId'),array(
		    'status' => 'unpublish',
		    'source' => 'web',
	    ));
    $shopRepo = \App\Factory\RepositoryFactory::shopsReposity();
    $shopPlanInfo = $shopRepo->shopPlansInfo(session('shopId'));

@endphp

<div class="sidebar-wrap">
    <div class="nav-sidebar-top">
        <div class="logo">
            <a href=""><img src="{{ URL::asset('images/backend/alireviews-logo.png') }}" alt="Ali Reviews"></a>
            <span class="nav-sidebar-close"><i class="demo-icon icon-cancel-4"></i></span>
        </div>
    </div>
    <div class="sidebar-container">
        <nav class="nav-sidebar">
            <h2>
                <a class="sidebarDashboard {{ ($currentRouteName == 'apps.dashboard') ? 'active' : '' }}" href="{{ route('apps.dashboard') }}">
                    {{ __('commons.sidebar_dashboard') }}
                    <!-- <div class="tooltip fade bottom in" role="tooltip">
                        <div class="tooltip-arrow"></div>
                        <div class="tooltip-inner">
                            <p>You can always go to Dashboard to watch how to use videos</p>
                        </div>
                    </div> -->
                </a>
            </h2>
            <ul class="nav-sidebar-list">
                <li class="{{ ($currentRouteName == 'products.list') ? 'active' : '' }} {{ ($currentRouteName == 'reviews.review_approve') ? 'active' : '' }} {{ ($currentRouteName == 'reviews.history_reviews') ? 'active' : '' }} {{ ($currentRouteName == 'reviews.createReviewsPage') ? 'active' : '' }}">
                    <a href="{{ route('products.list') }}"><span class="sidebar-img-icon"><img src="{{ URL::asset('images/backend/manage-review-icon.png') }}" alt=""></span>{{ __('commons.sidebar_manage_reviews') }}</a>
                    <ul class="nav-subsidebar-list">
                        <li class="{{ ($currentRouteName == 'reviews.review_approve') ? 'active' : '' }}">
                            <a href="{{ route('reviews.review_approve') }}"><span class="sidebar-img-icon"><img class="img-1" src="{{ URL::asset('images/backend/pending-approve-icon.png') }}"><img class="img-2" src="{{ URL::asset('images/backend/pending-approve-active-icon.png') }}" alt=""></span>{{ __('commons.sidebar_reviews_approve') }} {!!  !empty($listReviewApprove->total()) ? '<span class="total_review_approve">'.$listReviewApprove->total().'</span>' : '' !!}</a>
                        </li>
                        <li class="{{ ($currentRouteName == 'reviews.history_reviews') ? 'active' : '' }}">
                            <a href="{{ route('reviews.history_reviews') }}"><span class="sidebar-img-icon"><img class="img-1" src="{{ URL::asset('images/backend/history-review-icon.png') }}" alt=""><img class="img-2" src="{{ URL::asset('images/backend/history-review-active-icon.png') }}" alt=""></span>{{ __('commons.sidebar_history_reviews') }} </a>
                        </li>
                        <li class="createReviewsPage {{ ($currentRouteName == 'reviews.createReviewsPage') ? 'active' : '' }}">
                            <a href="{{ route('reviews.createReviewsPage') }}"><span class="sidebar-img-icon"><img class="img-1" src="{{ URL::asset('images/backend/creating-review-page-icon.png') }}" alt=""><img class="img-2" src="{{ URL::asset('images/backend/creating-review-page-active-icon.png') }}" alt=""></span>{{ __('commons.sidebar_create_reviews_page') }} </a>
                            @if( empty($shopPlanInfo['planInfo']) or empty($shopPlanInfo['planInfo']['reviews_page']))
                                <div class="tooltip fade bottom in" role="tooltip">
                                    <div class="tooltip-arrow"></div>
                                    <div class="tooltip-inner">
                                        <span>Only available in<br> Premium version</span>
                                        <a class="ars-btn" href="{{ route('apps.upgrade') }}">Upgrade to Premium
                                            version</a>
                                    </div>
                                </div>
                            @endIf
                        </li>
                        <li class="createReviewsPage">
                            <a href="https://app.oberlo.com/products" target="_blank"><span class="sidebar-img-icon"><img class="img-1" src="{{ URL::asset('images/backend/import-oberlo-icon.png') }}" alt=""><img class="img-2" src="{{ URL::asset('images/backend/import-oberlo-active-icon.png') }}" alt=""></span>{{ __('commons.sidebar_import_from_oberlo') }} </a>
                            @if( empty($shopPlanInfo['planInfo']) or empty($shopPlanInfo['planInfo']['add_from_operlo']))
                                <div class="tooltip fade bottom in" role="tooltip">
                                    <div class="tooltip-arrow"></div>
                                    <div class="tooltip-inner">
                                        <span>Only available in<br> Unlimited version</span>
                                        <a class="ars-btn" href="{{ route('apps.upgrade') }}">Upgrade to Unlimited
                                            version</a>
                                    </div>
                                </div>
                            @endIf
                        </li>
                    </ul>
                </li>

                <li class="{{ ($currentRouteName == 'settings.view') ? 'active' : '' }} {{ ($currentRouteName == 'settings.localTranslate') ? 'active' : '' }} {{ ($currentRouteName == 'settings.commentsDefault') ? 'active' : '' }}">
                    <a href="{{ route('settings.view') }}"><span class="sidebar-img-icon"><img src="{{ URL::asset('images/backend/general-settings-icon.png') }}" alt=""></span>{{ __('commons.sidebar_general_settings') }}</a>
                    <ul class="nav-subsidebar-list">
                        <li class="{{ ($currentRouteName == 'settings.localTranslate') ? 'active' : '' }}">
                            <a href="{{ route('settings.localTranslate') }}"><span class="sidebar-img-icon"><img class="img-1" src="{{ URL::asset('images/backend/local-translate-icon.png') }}" alt=""><img class="img-2" src="{{ URL::asset('images/backend/local-translate-active-icon.png') }}" alt=""></span>{{ __('commons.sidebar_local_translate') }}</a>
                        </li>
                        
                    </ul>
                </li>

                <li  class="{{ ($currentRouteName == 'settings.themesSetting') ? 'active' : '' }} {{ ($currentRouteName == 'settings.themesEdit') ? 'active' : '' }} {{ ($currentRouteName == 'settings.themesStore') ? 'active' : '' }}">
                    <a href="{{ route('settings.themesSetting') }}"><span class="sidebar-img-icon"><img src="{{ URL::asset('images/backend/theme-template-icon.png') }}" alt=""></span>{{ __('commons.sidebar_theme_templates') }}</a>
                    <ul class="nav-subsidebar-list">
                        <li  class="{{ ($currentRouteName == 'settings.themesStore') ? 'active' : '' }}">
                            <a href="{{ route('settings.themesStore') }}"><span class="sidebar-img-icon"><img class="img-1" src="{{ URL::asset('images/backend/theme-store-icon.png') }}" alt=""><img class="img-2" src="{{ URL::asset('images/backend/theme-store-active-icon.png') }}" alt=""></span>{{ __('commons.sidebar_theme_store') }}</a>
                        </li>
                    </ul>
                </li>
                <li class="{{ ($currentRouteName == 'apps.getStart') ? 'active' : '' }}">
                    <a href="{{ route('apps.getStart') }}"><span class="sidebar-img-icon"><img src="{{ URL::asset('images/backend/get-started-icon.png') }}" alt=""></span>{{ __('commons.sidebar_get_start') }}</a>
                </li>
            </ul>
            <ul class="nav-sidebar-list nav-sidebar-list-last">
                <li>
                    {{-- <a href="mailto:support@fireapps.io?subject=[AliReviews]"><span class="sidebar-img-icon"><img src="{{ URL::asset('images/backend/support-icon.png') }}"></span>Support</a> --}}
                    <a href="javascript:void(0)" id="toggleBeacon"><span class="sidebar-img-icon"><img src="{{ URL::asset('images/backend/support-icon.png') }}"></span>Support</a>
                </li>
                <li><a href="https://help.fireapps.io/welcome-to-fireapps/get-started/payment-refund-policy" target="_blank"><span class="sidebar-img-icon"><img src="{{ URL::asset('images/backend/privacy-policy-icon.png') }}"></span>Payment Policy</a></li>
                <li><a href="https://help.fireapps.io/" target="_blank"><span class="sidebar-img-icon"><img src="{{ URL::asset('images/backend/help-faq-icon.png') }}"></span>Help / FAQ</a></li>
                <li class="{{ ($currentRouteName == 'apps.upgrade') ? 'active' : '' }}">
                    <a href="{{ route('apps.upgrade') }}"><span class="sidebar-img-icon"><img src="{{ URL::asset('images/backend/choose-plan-icon.png') }}"></span>Choose your plan</a>
                </li>
            </ul>
            {{--<h2 class="nav-app-title">More from FireApps</h2>
            <ul class="nav-app-list">
                <li>
                    <a href="https://apps.shopify.com/shipping-information-by-fireapps" target="_blank"><img src="{{ URL::asset('images/backend/shipping-info-icon.png') }}" alt="Shipping Information">Shipping
                        Information</a>
                </li>
                <li>
                    <a href="https://apps.shopify.com/real-time-visitor" target="_blank"><img src="{{ URL::asset('images/backend/realtime-visitor-icon.png') }}" alt="Real Time Visitors">Real
                        Time Visitors</a>
                </li>
                <li>
                    <a href="" target="_blank"><img src="{{ URL::asset('images/backend/upsell-icon.png') }}" alt="Upsell and Cross-Sell">
                        Upsell and Cross-Sell</a>
                </li>
            </ul>--}}
            {{--<div class="more-fireapps-wrap" style="display: none">
                <a class="more-fireapps" href="https://apps.shopify.com/partners/developer-4d9a2b35fefa4208" target="_blank">View All <i class="demo-icon icon-right-open-mini"></i></a>
            </div>--}}
        </nav>
    </div>
    <div class="tooltip-dashboard tooltip fade right in" role="tooltip">
        <div class="tooltip-arrow"></div>
        <div class="tooltip-inner">
            <p>You can always go to Dashboard to watch how to use videos</p>
        </div>
    </div>

    <!-- 
    <div class="tooltip-policy tooltip fade right in" role="tooltip">
        <div class="tooltip-arrow"></div>
        <div class="tooltip-inner">
            <p>We've just updated our Policy, please kindly check before using the app</p>
        </div>
    </div>
    -->
</div>

@section('modals')
    @include('modal-mkt-6-popup.popup-1')
@endsection
@php
    $currentRouteName = \Illuminate\Support\Facades\Route::currentRouteName();
    $commentRepo = \App\Factory\RepositoryFactory::commentBackEndRepository();
    $listReviewApprove = $commentRepo->all( session('shopId'),array(
		    'status' => 'unpublish',
		    'source' => 'web',
	    ));
    $shopRepo = \App\Factory\RepositoryFactory::shopsReposity();
    $shopPlanInfo = $shopRepo->shopPlansInfo(session('shopId'));
@endphp

<aside class="main-sidebar">
    <div class="wrapper-sidebar">
        <ul class="sidebar-menu tree">
            <li class="{{ ($currentRouteName == 'apps.dashboard') ? 'active' : '' }}">
                <a href="{{ route('apps.dashboard')}}">
                    <span>
                        <i class="alimn alimn-dashboard"></i>
                        <i class="alimn alimn-dashboard_active"></i>
                    </span>
                    Dashboard
                </a>
            </li>

            <li 
                class="{{ (
                            $currentRouteName == 'products.list' 
                            || $currentRouteName == 'reviews.review_approve' 
                            || $currentRouteName == 'reviews.createReviewsPage'
                        ) ? 'active' : '' }}">
                <a href="{{ route('products.list') }}" class="icon-down">
                    <span>
                        <i class="alimn alimn-manage-reviews"></i>
                        <i class="alimn alimn-manage-reviews_active"></i>
                    </span>
                    Manage Reviews
                </a>
                <div class="panel-collapse collapse">
                    <div class="panel-body">
                        <ul>
                            <li class="{{ ($currentRouteName == 'reviews.review_approve') ? 'active' : '' }}">
                                <a href="{{ route('reviews.review_approve') }}">
                                    <span class="hidden">
                                        <i class="alimn alimn-pending-reviews"></i>
                                        <i class="alimn alimn-pending-reviews_active"></i>
                                    </span>
                                    Pending Reviews
                                    {!!  !empty($listReviewApprove->total()) ? '<div class="total_review_approve">'.$listReviewApprove->total().'</div>' : '' !!}
                                </a>
                            </li>
                            <li class="{{ ($currentRouteName == 'reviews.createReviewsPage') ? 'active' : '' }}">
                                <a
                                    @if( empty($shopPlanInfo['planInfo']) or empty($shopPlanInfo['planInfo']['reviews_page']))
                                        class="rel-tooltip"
                                        place-tooltip="bottom"
                                        data-tooltip="{!! __('commons.tooltip_paid') !!}"
                                    @else
                                        href="{{ route('reviews.createReviewsPage') }}"
                                    @endIf
                                >
                                    <span class="hidden">
                                        <i class="alimn alimn-create-reviews"></i>
                                        <i class="alimn alimn-create-reviews_active"></i>
                                    </span>
                                    Create Review Page
                                </a>
                            </li>

                            <li>
                                <a 
                                    href="https://aliorders.fireapps.io/products" 
                                    {{-- id="isAliordersLoginCurrentShop"  --}}
                                    target="_blank">
                                    Import from Ali Orders
                                </a>
                            </li>
                            
                            <li>
                                <a
                                    @if( empty($shopPlanInfo['planInfo']) or empty($shopPlanInfo['planInfo']['add_from_operlo']))
                                        class="rel-tooltip"
                                        place-tooltip="bottom"
                                        data-tooltip="{!! __('commons.tooltip_unlimited') !!}"
                                    @else
                                        {{-- id="isOberloLoginCurrentShop" --}}
                                        href="https://app.oberlo.com/products" target="_blank"
                                    @endif
                                >
                                    <span class="hidden">
                                        <i class="alimn alimn-import-obelo"></i>
                                        <i class="alimn alimn-import-obelo_active"></i>
                                    </span>
                                    Import from Oberlo
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
            <li class="{{ ($currentRouteName == 'settings.view') ? 'active' : '' }}">
                <a href="{{ route('settings.view')}}">
                    <span>
                        <i class="alimn alimn-general-setting"></i>
                        <i class="alimn alimn-general-setting_active"></i>
                    </span>
                    General Settings
                </a>
            </li>
            <li class="{{ ($currentRouteName == 'settings.themesSetting' ) ? 'active' : '' }}">
                <a href="{{ route('settings.themesSetting') }}">
                    <span>
                        <i class="alimn alimn-theme-settings"></i>
                        <i class="alimn alimn-theme-settings_active"></i>
                    </span>
                    Theme settings
                </a>
            </li>
            <li class="{{ ($currentRouteName == 'settings.localTranslate') ? 'active' : '' }}">
                <a href="{{ route('settings.localTranslate')}}">
                    <span>
                        <i class="alimn alimn-translation"></i>
                        <i class="alimn alimn-translation_active"></i>
                    </span>
                    Translation
                </a>
            </li>

            <li class="{{ ($currentRouteName == 'apps.upgrade') ? 'active' : '' }}">
                <a href="{{ route('apps.upgrade') }}">
                    <span>
                        <i class="alimn alimn-upgrade-version"></i>
                        <i class="alimn alimn-upgrade-version_active"></i>
                    </span>
                    Pricing
                </a>
            </li>

            <li class="divide-sidebar">
                <div></div>
            </li>

            <li class="alrv-toggle-intercom">
                <a href="javascript:void(0)">
                    <span>
                        <i class="alimn alimn-support"></i>
                        <i class="alimn alimn-support_active"></i>
                    </span>
                    Support
                </a>
            </li>

            <li>
                <a href="https://help.fireapps.io/welcome-to-fireapps/get-started/payment-refund-policy" target="_blank">
                    <span>
                        <i class="alimn alimn-payment"></i>
                        <i class="alimn alimn-payment_active"></i>
                    </span>
                    Payment Policy
                </a>
            </li>

            <li>
                <a href="https://help.fireapps.io/ali-reviews" target="_blank">
                    <span>
                        <i class="alimn alimn-faq"></i>
                        <i class="alimn alimn-faq_active"></i>
                    </span>
                    Help / FAQ
                </a>
            </li>

            <li>
                <a href="https://www.facebook.com/groups/fireapps.io" target="_blank">
                    <span>
                        <i class="alimn alimn-community"></i>
                        <i class="alimn alimn-community_active"></i>
                    </span>
                    <div class="d-inline-block">Community</div>
                    <div class="d-inline-block status-new">New</div>
                </a>
            </li>
        </ul>
    </div>
</aside>

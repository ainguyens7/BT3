@extends('layout.backend',['page_title' => 'Trial Premium For Free User'])


@section('styles')
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-KR73PVX');</script>
    <!-- End Google Tag Manager -->
@endsection

@section('header')
    @include('layout.header', ['page_title' => 'Trial Premium For Free User'])
@endsection

@section('container_content')
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KR73PVX"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <div class="upgrade-wrap">
        <p style="font-weight: 600;">
            Before you start the trial, there are a few things you should know: <br>
            1. You must approve charge first to start the trial. You will not get charged if you cancel the trial within first <b>7 days</b>. <br>
            2. Downgrade to Free version will wipe all the data.
        </p>

        <ul class="upgrade-item-list row">
            {{-- Free trial --}}
            <!-- <li class="col-sm-4">
                <div class="upgrade-block upgrade-standard">
                    <div class="upgrade-block-top">
                        <p>BASIC</p>
                        <h2>Free</h2>
                        <div class="confirm-policy" style="color: white;">
                            By clicking this button, you agree<br> with our <a href="http://help.fireapps.io/category/26-beginner" target="_blank">"Terms & Policies"</a>
                        </div>
                            <a href="#" class="ars-btn" id="getTrial">Trial</a>
                    </div>
                    
                    <div class="upgrade-block-content">
                        <ul>
                            <li>
                                <p><strong>Unlimited</strong> reviews imported</p>
                                <p><strong>10</strong> products get reviews</p>
                                <p><strong>5</strong> reviews published / product</p>
                                <p><strong>3</strong> layout options</p>
                            </li>
                            <li>
                                <p>Write review manually</p>
                                <p>Display review properties</p>
                                <p>Verified reviews icon</p>
                                <p>Manage reviews</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </li> -->
            {{-- End:Free trial --}}

            <li class="col-sm-6">
                <form method="post" class="form-trial">
                    <div class="upgrade-block upgrade-pro">
                        <div class="upgrade-block-top">
                            <p>PRO</p>
                            <h2><span class="upgrade-title-currency">$</span>9<span class="upgrade-title-month">/ MO</span></h2>
                            <div class="confirm-policy" style="color: white;">
                                By clicking this button, you agree<br> with our <a href="http://help.fireapps.io/category/26-beginner" target="_blank">"Terms & Policies"</a>
                            </div>
                            <button type="button" class="btn-add-discount-code ars-btn" data-plan="premium" id="getPro">Trial</button>
                            <div>
                                <!--
                                <a class="btn-add-discount-code have-voucher-btn" data-plan="unlimited" href="javascript:void(0)" id="trial-pro-upgrade-voucher">I Have Voucher</a>-->
                            </div>
                        </div>
                        <div class="upgrade-block-content">
                            <ul>
                                <li>
                                    <p><strong>*Unlimited</strong> reviews imported<br><span>(Up to 1500 reviews/product)</span></p>
                                    <p><strong>Unlimited</strong> reviews products</p>
                                    <p><strong>Unlimited</strong> reviews published</p>
                                    <p><strong>5</strong> layout options</p>
                                    <p><strong>3</strong> theme options</p>
                                    <p><strong>Review keyword blacklist</strong></p>
                                </li>
                                <li>
                                    <p>Write review manually</p>
                                    <p>Display review properties</p>
                                    <p>Verified reviews icon</p>
                                    <p>Manage reviews</p>
                                    <p>Remove branded logo</p>
                                    <p>Create review showcase page</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="plan" value="premium">
                </form>
            </li>


            <li class="col-sm-6">
                <form method="post" class="form-trial">
                    <div class="upgrade-block upgrade-premium">
                        <div class="upgrade-block-top">
                            <p>UNLIMITED</p>
                            <h2><span class="upgrade-title-currency">$</span>14.9 <span
                                        class="upgrade-title-month">/ MO</span></h2>

                            <div class="confirm-policy" style="color: white;">
                                By clicking this button, you agree<br> with our <a href="http://help.fireapps.io/category/26-beginner" target="_blank">"Terms & Policies"</a>
                            </div>

                            <button type="button" class="btn-add-discount-code  ars-btn" data-plan="unlimited" id="getUnlimited">Trial</button>
                            <div>
                                <!--
                                <a class="btn-add-discount-code have-voucher-btn" data-plan="unlimited" href="javascript:void(0)" id="trial-unlimited-upgrade-voucher">I Have Voucher</a>-->
                            </div>
                        </div>
                        <div class="upgrade-block-content">
                            <ul>
                                <li>
                                    <p><strong>*Unlimited</strong> reviews imported<br><span>(Up to 1500 reviews/product)</span></p>
                                    <p><strong>Unlimited</strong> reviews products</p>
                                    <p><strong>Unlimited</strong> reviews published</p>
                                    <p><strong>Unlimited</strong> layout options</p>
                                    <p><strong>Unlimited</strong> theme options</p>
                                    <p><strong>Review keyword blacklist</strong></p>
                                    <p><strong>Integrated Oberlo</strong></p>
                                </li>
                                <li>
                                    <p>Write review manually</p>
                                    <p>Display review properties</p>
                                    <p>Verified reviews icon</p>
                                    <p>Manage reviews</p>
                                    <p>Remove branded logo</p>
                                    <p>Create review showcase page</p>
                                    <p>Advance styling (Edit CSS)</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="plan" value="unlimited">
                </form>
            </li>
        </ul>
    </div>

    @include('apps.plans.modal_add_discount', ['trial_day' => $trial_day])
    @include('apps.modal_delete_data')
@endsection
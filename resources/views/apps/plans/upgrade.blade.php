@extends('layout.dashboard',['page_title' => 'Pricing'])

@section('head_script')
    @include('third-party/gtag_upgrade')
@endsection

@section('breadcrumb')
    @include('partials/breadcrumb', ['breadcrumb_link' => 'Pricing'])
@endsection

@section('body_content')
    <div class="wrapper-space wrapper-space--45 bg-white upgrade-version-page">
        <div class="clearfix"></div>
        {{-- <h3>Choose your plan</h3> --}}

        <div class="table-responsive">
            <table class="table table--noline mar-b-0" cellspacing="0">
                <thead>
                    <tr class="style-button">
                        <th style="vertical-align: middle;">
                            <strong class="fz21 color-dark-blue">CHOOSE YOUR PLAN</strong>
                        </th>

                        <th style="border-right: none;">
                            <p class="mar-b-20">FREE</p>
                            <p class="mar-b-10 fw500 color-dark-blue"><strong>${{ config('plans.free.price') }}</strong>/ month</p>
                            <!-- form free !-->
                            @include('includes/upgrade_user_action_button_free', ['shopPlanInfo' => $shopPlanInfo])
                            <!-- end form free !-->
                        </th>

                        <th class="th-recommend" style="border-right: none;">
                            <p class="mar-b-20">PRO</p>
                            <p class="mar-b-10 fw500"><strong>${{ config('plans.premium.price') }}</strong>/ month</p>
                            <!-- form premium !-->
                            @include('includes/upgrade_form', ['shopPlanInfo' => $shopPlanInfo, 'plan' => config('plans.premium.name')])
                            <!-- end form premium !-->
                        </th>

                        <th>
                            <p class="mar-b-20">UNLIMITED</p>
                            <p class="mar-b-10 fw500 color-dark-blue"><strong>${{ config('plans.unlimited.price') }}</strong>/ month <br>
                            <!-- form unlimited !-->
                            @include('includes/upgrade_form', ['shopPlanInfo' => $shopPlanInfo, 'plan' => config('plans.unlimited.name')])
                            <!-- end form unlimited !-->
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Unlimited</strong> reviews imported per product</td>
                        <td><img src="{{ asset('images/icons/tick.png') }}" width="15" alt=""></td>
                        <td><img src="{{ asset('images/icons/tick.png') }}" width="15" alt=""></td>
                        <td><img src="{{ asset('images/icons/tick.png') }}" width="15" alt=""></td>
                    </tr>
                    <tr>
                        <td>Number of products can import reviews</td>
                        <td>10</td>
                        <td>Unlimited</td>
                        <td>Unlimited</td>
                    </tr>
                    <tr>
                        <td>Number of imported reviews can publish <br> (per product)</td>
                        <td>5</td>
                        <td>Unlimited</td>
                        <td>Unlimited</td>
                    </tr>
                    <tr>
                        <td>Sort & pin top reviews</td>
                        <td><img src="{{ asset('images/icons/tick.png') }}" width="15" alt=""></td>
                        <td><img src="{{ asset('images/icons/tick.png') }}" width="15" alt=""></td>
                        <td><img src="{{ asset('images/icons/tick.png') }}" width="15" alt=""></td>
                    </tr>
                    <tr>
                        <td>Advance import filter</td>
                        <td><img src="{{ asset('images/icons/tick.png') }}" width="15" alt=""></td>
                        <td><img src="{{ asset('images/icons/tick.png') }}" width="15" alt=""></td>
                        <td><img src="{{ asset('images/icons/tick.png') }}" width="15" alt=""></td>
                    </tr>
                    <tr>
                        <td>Write review manually</td>
                        <td><img src="{{ asset('images/icons/tick.png') }}" width="15" alt=""></td>
                        <td><img src="{{ asset('images/icons/tick.png') }}" width="15" alt=""></td>
                        <td><img src="{{ asset('images/icons/tick.png') }}" width="15" alt=""></td>
                    </tr>
                    <tr>
                        <td>Verified reviews icon</td>
                        <td><img src="{{ asset('images/icons/tick.png') }}" width="15" alt=""></td>
                        <td><img src="{{ asset('images/icons/tick.png') }}" width="15" alt=""></td>
                        <td><img src="{{ asset('images/icons/tick.png') }}" width="15" alt=""></td>
                    </tr>
                    <tr>
                        <td>Manage reviews (Edit, Delete, Hide)</td>
                        <td><img src="{{ asset('images/icons/tick.png') }}" width="15" alt=""></td>
                        <td><img src="{{ asset('images/icons/tick.png') }}" width="15" alt=""></td>
                        <td><img src="{{ asset('images/icons/tick.png') }}" width="15" alt=""></td>
                    </tr>
                    <tr>
                        <td>Remove branded logo</td>
                        <td></td>
                        <td><img src="{{ asset('images/icons/tick.png') }}" width="15" alt=""></td>
                        <td><img src="{{ asset('images/icons/tick.png') }}" width="15" alt=""></td>
                    </tr>
                    <tr>
                        <td>Review keyword blacklist</td>
                        <td></td>
                        <td><img src="{{ asset('images/icons/tick.png') }}" width="15" alt=""></td>
                        <td><img src="{{ asset('images/icons/tick.png') }}" width="15" alt=""></td>
                    </tr>
                    <tr>
                        <td>Create review showcase page</td>
                        <td></td>
                        <td><img src="{{ asset('images/icons/tick.png') }}" width="15" alt=""></td>
                        <td><img src="{{ asset('images/icons/tick.png') }}" width="15" alt=""></td>
                    </tr>
                    <tr>
                        <td><span class="obelo">Integrated Oberlo for quick importing</span></td>
                        <td></td>
                        <td></td>
                        <td><img src="{{ asset('images/icons/tick.png') }}" width="15" alt=""></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @include('apps.plans.modal_add_discount')
    @include('apps.modal_delete_data')
@endsection


@section('body_script')
    <script>
        const currentPlan = "{{ $shopPlanInfo['planInfo']['name'] }}";
        $('.btn-remove-data').on('click', function() {
            $('#deleteDataModal').on('show.bs.modal', function() {
                Intercom("update", {last_request_at: (new Date()).getTime()/1000});
                Intercom('trackEvent', 'click_downgrade');
                Intercom("update", {last_request_at: (new Date()).getTime()/1000});
                $('.change-text-plan').text(`All your ${currentPlan} data will be off if you downgrade.`)
            })
        })
    </script>
@endsection

@extends('layout.dashboard',['page_title' => 'Re Install'])

@section('breadcrumb')
    @include('partials/breadcrumb', ['breadcrumb_link' => 'Re Install'])
@endsection

@section('body_content')
    <div class="reinstall-page-blind"></div>

    <div class="reinstall-page">
        <div class="choose-plan-content">
            <div style="transform: translate(0, 0);">
                <h3>Reinstalling Ali Reviews</h3>
                <h4>Please click <strong class="text-uppercase">ACCEPT</strong> to continue using your current plan, or change to the plan you want</h4>

                <div class="row row-plans">
                    <div class="col-lg-4">
                        <div class="plan-item">
                            <p class="plan">Basic Plan</p>
                            <p class="price">Free</p>
                            <p class="every-month">per month</p>
                            <hr>
                            <p class="description">Get all the standard features but limit importing review up to 10 products. It's a nice way to try the app</p>
                            
                            @if($shopPlanInfo['planInfo']['name'] =='free')
                                <a class="button active-accept" href="{{ route('products.list') }}">ACCEPT</a>
                            @else
                                <a class="button btn-remove-data" data-plan='free' href="javascript:void(0)">DOWNGRADE</a>
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <form method="post" action="{{ route('apps.upgradeHandle') }}">
                            <div class="plan-item">
                                <p class="plan">{{ config('plans.alias.premium') }} Plan</p>
                                <p class="price">${{ config('plans.premium.price') }}</p>
                                <p class="every-month">per month</p>
                                <hr>
                                <p class="description">Recommended by FireApps with Unlimited products & Unlimited reviews. Suits those who're running dropshipping</p>
                            
                                @if($shopPlanInfo['planInfo']['name'] =='free')
                                    <button type="submit" class="button">UPGRADE</button>
                                @else
                                    @if($shopPlanInfo['planInfo']['name'] =='premium')
                                        <a href="{{ route('products.list') }}" class="button active-accept">ACCEPT</a>
                                    @else
                                        <a class="button btn-remove-data" data-plan='premium' href="javascript:void(0)">DOWNGRADE</a>
                                    @endif
                                @endIf
                            </div>

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="plan" value="premium">
                        </form>
                    </div>

                    <div class="col-lg-4">
                        <form method="post" action="{{ route('apps.upgradeHandle') }}">
                            <div class="plan-item">
                                <p class="plan">Unlimited Plan</p>
                                <p class="price">${{ config('plans.unlimited.price') }}</p>
                                <p class="every-month">per month</p>
                                <hr>
                                <p class="description">Unlock all the best of Ali Reviews, no limitation with extra helpful features like Oberlo Integration, Bulk Import Action.</p>
                                
                                @if(!empty($shopPlanInfo['planInfo']) &&  $shopPlanInfo['planInfo']['name'] =='unlimited')
                                    <a href="{{ route('products.list') }}" class="button active-accept">ACCEPT</a>
                                @else
                                    <button type="submit" class="button">UPGRADE</button>
                                @endIf
                            </div>

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="plan" value="unlimited">
                        </form>
                    </div>
                </div>

                <h4 style="line-height: 1.6; margin-bottom: 0; margin-top: 20px;">Remember, changing plan when reinstalling may occur multiple charge. <br> Please contact us if you need any support.</h4>
            </div>
        </div>
    </div>

    @include('apps.modal_delete_data')
@endsection

@section('body_script')
    <script>
        document.body.classList.add('modal-open');

        const currentPlan = "{{ $shopPlanInfo['planInfo']['name'] }}" == 'premium' ? 'pro' : "{{ $shopPlanInfo['planInfo']['name'] }}";
        $('.btn-remove-data').on('click', function() {
            $('#deleteDataModal').on('show.bs.modal', function() {
                Intercom("update", {last_request_at: (new Date()).getTime()/1000});
                Intercom('trackEvent', 'click_downgrade');
                Intercom("update", {last_request_at: (new Date()).getTime()/1000});
                $('.change-text-plan').html(`All your <span class="text-uppercase">${currentPlan}</span> data will be off if you downgrade.`)

                $('.reinstall-page-blind').hide();
                $('.reinstall-page').hide();
            });

            $('#deleteDataModal').on('hidden.bs.modal', function() {
                document.body.classList.add('modal-open');
                $('.reinstall-page-blind').show();
                $('.reinstall-page').show();
            });
        })
    </script>
@endsection
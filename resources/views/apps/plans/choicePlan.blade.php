@extends('layout.dashboard',['page_title' => 'Choose Plan'])

@section('breadcrumb')
    @include('partials/breadcrumb', ['breadcrumb_link' => 'Choose plan'])
@endsection

@section('body_content')
    <div class="choose-plan-blind"></div>
    <div class="choose-plan-page">
        <div class="choose-plan-content">
            <div style="transform: translate(0, 0);">
                <h3>Try one of these plans for FREE <br> and get the BEST of Ali Reviews!</h3>
                <p class=""><a href="https://help.fireapps.io/welcome-to-fireapps/get-started/payment-refund-policy" class="color-grey-800" target="_blank">7-day money back guarantee</a></p>
                <div class="row row-plans" style="max-width: 715px; margin-left: auto; margin-right: auto;">
                    <div class="col-lg-6">
                        <form method="post" action="{{ route('apps.upgradeHandle') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="plan" value="premium">
                            <div class="plan-item active">
                                <p class="recommend">RECOMMENDED</p>
                                <p class="plan">PRO Plan</p>
                                <p class="price">${{ config('plans.premium.price') }} <span class="every-month">per month</span></p>
                                <hr>
                                <p class="description"><strong class="fw600">Unlimited</strong> Products Get Reviews</p>
                                <p class="description"><strong class="fw600">Unlimited</strong> Reviews Published Per Product</p>
                                <p class="description">Remove Branded Logo</p>
                                <p class="description">Review Showcase Page</p>
                                <button type="submit" class="button GET_STARTED_PRO">GET STARTED</button>
                            </div>
                        </form>
                    </div>

                    <div class="col-lg-6">
                        <form method="post" action="{{ route('apps.upgradeHandle') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="plan" value="unlimited">
                            <div class="plan-item">
                                <p class="plan">Unlimited Plan</p>
                                <p class="price">${{ config('plans.unlimited.price') }} <span class="every-month">per month</span></p>
                                <hr>
                                <p class="description"><strong class="fw600">Unlimited</strong> Products Get Reviews</p>
                                <p class="description"><strong class="fw600">Unlimited</strong> Reviews Published Per Product</p>
                                <p class="description">Remove Branded Logo</p>
                                <p class="description">Review Showcase Page</p>
                                <p class="description__special">Integrated Oberlo For Quick Importing</p>
                                <button href="submit" class="button GET_STARTED_UN">GET STARTED</button>
                            </div>
                        </form>
                    </div>
                </div>

                <span class="all-plan">
                    <i class="material-icons">local_offer</i>
                    Or click here to start using our 
                    <form method="post" action="{{ route('apps.upgradeHandle') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="plan" value="free">
                        <button type="submit" class="GET_STARTED_FREE">FREE PLAN</button>
                    </form>
                </span>
            </div>
        </div>
    </div>
@endsection

@section('body_script')
<script>
    document.body.classList.add('modal-open');
</script>
@endsection
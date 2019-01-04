@extends('layout.backend', ['page_title' => 'Theme Store'])

@section('header')
    @include('layout.header', ['page_title' => 'Theme Store'])
@endsection

@section('container_content')
    @include('sections.alert_error')
    @include('sections.alert_success')
    <form id="form_ThemesStore" action="" method="post" novalidate>
        <div class="theme-store-wrap  {{ $shopPlanInfo['name'] == 'free' ? 'disabled' : '' }}">
            <h2>Themes Store
                @if($shopPlanInfo['name'] == 'free')
                    <div class="tooltip fade right in" role="tooltip">
                        <div class="tooltip-arrow"></div>
                        <div class="tooltip-inner">
                            <span>Only available in<br> Premium version</span>
                            <a class="ars-btn" href="{{ route('apps.upgrade') }}">Upgrade to Premium
                                version</a>
                        </div>
                    </div>
                @endIf
            </h2>
            <p>Choose a style for your Reviews section</p>
            <div class="ctn-theme-store-wrap">
                <h3>1. Rating star</h3>
                <div class="popular-block-wrap">
                    <span class="popular-block-title">Popular</span>
                    <div class="popular-block">
                        <label class="ars-radio" for="rating-point-1">
                            <input id="rating-point-1" type="radio" name="rating_point"
                                   value="1" {{ !empty($settings['rating_point'] && $settings['rating_point'] == 1) ?'checked' :''  }}>
                            <span class="icon-block-wrap">
                            <span class="icon-block">
                                <img src="{{ URL::asset('images/backend/rating-point-1.png') }}" alt="">
                            </span>
                            <span class="ars-checked"></span>
                        </span>
                        </label>
                    </div>
                    <div class="popular-block {{ (empty($shopPlanInfo['rating_point']) or !in_array(2,$shopPlanInfo['rating_point']) ) ? 'disabled' : '' }}">
                        <label class="ars-radio" for="rating-point-2">
                            <input id="rating-point-2" type="radio" name="rating_point"
                                   value="2" {{ !empty($settings['rating_point'] && $settings['rating_point'] == 2) ?'checked' :'' }}>
                            <span class="icon-block-wrap">
                            <span class="icon-block">
                                <img src="{{ URL::asset('images/backend/rating-point-2.png') }}" alt="">
                            </span>
                            <span class="ars-checked"></span>
                        </span>
                        </label>
                    </div>
                    <div class="popular-block {{ (empty($shopPlanInfo['rating_point']) or !in_array(3,$shopPlanInfo['rating_point']) ) ? 'disabled' : '' }}">
                        <label class="ars-radio" for="rating-point-3">
                            <input id="rating-point-3" type="radio" name="rating_point"
                                   value="3" {{ !empty($settings['rating_point'] && $settings['rating_point'] == 3) ?'checked' :'' }}>
                            <span class="icon-block-wrap">
                            <span class="icon-block">
                                <img src="{{ URL::asset('images/backend/rating-point-3.png') }}" alt="">
                            </span>
                            <span class="ars-checked"></span>
                        </span>
                        </label>
                    </div>
                    <div class="popular-block {{ (empty($shopPlanInfo['rating_point']) or !in_array(4,$shopPlanInfo['rating_point']) ) ? 'disabled' : '' }}">
                        <label class="ars-radio" for="rating-point-4">
                            <input id="rating-point-4" type="radio" name="rating_point"
                                   value="4" {{ !empty($settings['rating_point'] && $settings['rating_point'] == 4) ?'checked' :'' }}>
                            <span class="icon-block-wrap">
                            <span class="icon-block">
                                <img src="{{ URL::asset('images/backend/rating-point-4.png') }}" alt="">
                            </span>
                            <span class="ars-checked"></span>
                        </span>
                        </label>
                    </div>
                    <div class="popular-block {{ (empty($shopPlanInfo['rating_point']) or !in_array(5,$shopPlanInfo['rating_point']) ) ? 'disabled' : '' }}">
                        <label class="ars-radio" for="rating-point-5">
                            <input id="rating-point-5" type="radio" name="rating_point"
                                   value="5" {{ !empty($settings['rating_point'] && $settings['rating_point'] == 5) ?'checked' :'' }}>
                            <span class="icon-block-wrap">
                            <span class="icon-block">
                                <img src="{{ URL::asset('images/backend/rating-point-5.png') }}" alt="">
                            </span>
                            <span class="ars-checked"></span>
                        </span>
                        </label>
                    </div>
                </div>
                <div class="popular-block-wrap">
                    <span class="popular-block-title">Season</span>
                    <div class="popular-block {{ (empty($shopPlanInfo['rating_point']) or !in_array(6,$shopPlanInfo['rating_point']) ) ? 'disabled' : '' }}">
                        <label class="ars-radio" for="rating-point-6">
                            <input id="rating-point-6" type="radio" name="rating_point"
                                   value="6" {{ !empty($settings['rating_point'] && $settings['rating_point'] == 6) ?'checked' :'' }}>
                            <span class="icon-block-wrap">
                            <span class="icon-block">
                                <img src="{{ URL::asset('images/backend/rating-point-6.png') }}" alt="">
                            </span>
                            <span class="ars-checked"></span>
                        </span>
                        </label>

                        @if(empty($shopPlanInfo['rating_point']) or !in_array(6,$shopPlanInfo['rating_point']))
                            <div class="tooltip fade right in" role="tooltip">
                                <div class="tooltip-arrow"></div>
                                <div class="tooltip-inner">
                                    <span>Only available in<br> Unlimited version</span>
                                    <a class="ars-btn" href="{{ route('apps.upgrade') }}">Upgrade to Unlimited
                                        version</a>
                                </div>
                            </div>
                        @endIf
                    </div>
                    <div class="popular-block {{ (empty($shopPlanInfo['rating_point']) or !in_array(7,$shopPlanInfo['rating_point']) ) ? 'disabled' : '' }}">
                        <label class="ars-radio" for="rating-point-7">
                            <input id="rating-point-7" type="radio" name="rating_point"
                                   value="7" {{ !empty($settings['rating_point'] && $settings['rating_point'] == 7) ?'checked' :'' }}>
                            <span class="icon-block-wrap">
                            <span class="icon-block">
                                <img src="{{ URL::asset('images/backend/rating-point-7.png') }}" alt="">
                            </span>
                            <span class="ars-checked"></span>
                        </span>
                        </label>

                        @if(empty($shopPlanInfo['rating_point']) or !in_array(7,$shopPlanInfo['rating_point']))
                            <div class="tooltip fade right in" role="tooltip">
                                <div class="tooltip-arrow"></div>
                                <div class="tooltip-inner">
                                    <span>Only available in<br> Unlimited version</span>
                                    <a class="ars-btn" href="{{ route('apps.upgrade') }}">Upgrade to Unlimited
                                        version</a>
                                </div>
                            </div>
                        @endIf
                    </div>
                    <div class="popular-block {{ (empty($shopPlanInfo['rating_point']) or !in_array(8,$shopPlanInfo['rating_point']) ) ? 'disabled' : '' }}">
                        <label class="ars-radio" for="rating-point-8">
                            <input id="rating-point-8" type="radio" name="rating_point"
                                   value="8" {{ !empty($settings['rating_point'] && $settings['rating_point'] == 8) ?'checked' :'' }}>
                            <span class="icon-block-wrap">
                            <span class="icon-block">
                                <img src="{{ URL::asset('images/backend/rating-point-8.png') }}" alt="">
                            </span>
                            <span class="ars-checked"></span>
                        </span>
                        </label>

                        @if(empty($shopPlanInfo['rating_point']) or !in_array(8,$shopPlanInfo['rating_point']))
                            <div class="tooltip fade right in" role="tooltip">
                                <div class="tooltip-arrow"></div>
                                <div class="tooltip-inner">
                                    <span>Only available in<br> Unlimited version</span>
                                    <a class="ars-btn" href="{{ route('apps.upgrade') }}">Upgrade to Unlimited
                                        version</a>
                                </div>
                            </div>
                        @endIf
                    </div>
                    <div class="popular-block {{ (empty($shopPlanInfo['rating_point']) or !in_array(9,$shopPlanInfo['rating_point']) ) ? 'disabled' : '' }}">
                        <label class="ars-radio" for="rating-point-9">
                            <input id="rating-point-9" type="radio" name="rating_point"
                                   value="9" {{ !empty($settings['rating_point'] && $settings['rating_point'] == 9) ?'checked' :'' }}>
                            <span class="icon-block-wrap">
                            <span class="icon-block">
                                <img src="{{ URL::asset('images/backend/rating-point-9.png') }}" alt="">
                            </span>
                            <span class="ars-checked"></span>
                        </span>
                        </label>

                        @if(empty($shopPlanInfo['rating_point']) or !in_array(9,$shopPlanInfo['rating_point']))
                            <div class="tooltip fade right in" role="tooltip">
                                <div class="tooltip-arrow"></div>
                                <div class="tooltip-inner">
                                    <span>Only available in<br> Unlimited version</span>
                                    <a class="ars-btn" href="{{ route('apps.upgrade') }}">Upgrade to Unlimited
                                        version</a>
                                </div>
                            </div>
                        @endIf
                    </div>
                    <div class="popular-block {{ (empty($shopPlanInfo['rating_point']) or !in_array(10,$shopPlanInfo['rating_point']) ) ? 'disabled' : '' }}">
                        <label class="ars-radio" for="rating-point-10">
                            <input id="rating-point-10" type="radio" name="rating_point"
                                   value="10" {{ !empty($settings['rating_point'] && $settings['rating_point'] == 10) ?'checked' :'' }}>
                            <span class="icon-block-wrap">
                            <span class="icon-block">
                                <img src="{{ URL::asset('images/backend/rating-point-10.png') }}" alt="">
                            </span>
                            <span class="ars-checked"></span>
                        </span>
                        </label>

                        @if(empty($shopPlanInfo['rating_point']) or !in_array(10,$shopPlanInfo['rating_point']))
                            <div class="tooltip fade right in" role="tooltip">
                                <div class="tooltip-arrow"></div>
                                <div class="tooltip-inner">
                                    <span>Only available in<br> Unlimited version</span>
                                    <a class="ars-btn" href="{{ route('apps.upgrade') }}">Upgrade to Unlimited
                                        version</a>
                                </div>
                            </div>
                        @endIf
                    </div>
                    <div class="popular-block {{ (empty($shopPlanInfo['rating_point']) or !in_array(11,$shopPlanInfo['rating_point']) ) ? 'disabled' : '' }}">
                        <label class="ars-radio" for="rating-point-11">
                            <input id="rating-point-11" type="radio" name="rating_point"
                                   value="11" {{ !empty($settings['rating_point'] && $settings['rating_point'] == 11) ?'checked' :'' }}>
                            <span class="icon-block-wrap">
                            <span class="icon-block">
                                <img src="{{ URL::asset('images/backend/rating-point-11.png') }}" alt="">
                            </span>
                            <span class="ars-checked"></span>
                        </span>
                        </label>

                        @if(empty($shopPlanInfo['rating_point']) or !in_array(11,$shopPlanInfo['rating_point']))
                            <div class="tooltip fade right in" role="tooltip">
                                <div class="tooltip-arrow"></div>
                                <div class="tooltip-inner">
                                    <span>Only available in<br> Unlimited version</span>
                                    <a class="ars-btn" href="{{ route('apps.upgrade') }}">Upgrade to Unlimited
                                        version</a>
                                </div>
                            </div>
                        @endIf
                    </div>

                    <!--Star new-->
                    <div class="popular-block {{ (empty($shopPlanInfo['rating_point']) or !in_array(12,$shopPlanInfo['rating_point']) ) ? 'disabled' : '' }}">
                        <label class="ars-radio" for="rating-point-12">
                            <input id="rating-point-12" type="radio" name="rating_point"
                                   value="12" {{ !empty($settings['rating_point'] && $settings['rating_point'] == 12) ?'checked' :'' }}>
                            <span class="icon-block-wrap">
                            <span class="icon-block">
                                <img src="{{ URL::asset('images/backend/rating-point-12.png') }}" alt="">
                            </span>
                            <span class="ars-checked"></span>
                        </span>
                        </label>

                        @if(empty($shopPlanInfo['rating_point']) or !in_array(12,$shopPlanInfo['rating_point']))
                            <div class="tooltip fade right in" role="tooltip">
                                <div class="tooltip-arrow"></div>
                                <div class="tooltip-inner">
                                    <span>Only available in<br> Unlimited version</span>
                                    <a class="ars-btn" href="{{ route('apps.upgrade') }}">Upgrade to Unlimited
                                        version</a>
                                </div>
                            </div>
                        @endIf
                    </div>
                    <div class="popular-block {{ (empty($shopPlanInfo['rating_point']) or !in_array(13,$shopPlanInfo['rating_point']) ) ? 'disabled' : '' }}">
                        <label class="ars-radio" for="rating-point-13">
                            <input id="rating-point-13" type="radio" name="rating_point"
                                   value="13" {{ !empty($settings['rating_point'] && $settings['rating_point'] == 13) ?'checked' :'' }}>
                            <span class="icon-block-wrap">
                            <span class="icon-block">
                                <img src="{{ URL::asset('images/backend/rating-point-13.png') }}" alt="">
                            </span>
                            <span class="ars-checked"></span>
                        </span>
                        </label>

                        @if(empty($shopPlanInfo['rating_point']) or !in_array(13,$shopPlanInfo['rating_point']))
                            <div class="tooltip fade right in" role="tooltip">
                                <div class="tooltip-arrow"></div>
                                <div class="tooltip-inner">
                                    <span>Only available in<br> Unlimited version</span>
                                    <a class="ars-btn" href="{{ route('apps.upgrade') }}">Upgrade to Unlimited
                                        version</a>
                                </div>
                            </div>
                        @endIf
                    </div>

                    <div class="popular-block {{ (empty($shopPlanInfo['rating_point']) or !in_array(14,$shopPlanInfo['rating_point']) ) ? 'disabled' : '' }}">
                        <label class="ars-radio" for="rating-point-14">
                            <input id="rating-point-14" type="radio" name="rating_point"
                                   value="14" {{ !empty($settings['rating_point'] && $settings['rating_point'] == 14) ?'checked' :'' }}>
                            <span class="icon-block-wrap">
                            <span class="icon-block">
                                <img src="{{ URL::asset('images/backend/rating-point-14.png') }}" alt="">
                            </span>
                            <span class="ars-checked"></span>
                        </span>
                        </label>

                        @if(empty($shopPlanInfo['rating_point']) or !in_array(14,$shopPlanInfo['rating_point']))
                            <div class="tooltip fade right in" role="tooltip">
                                <div class="tooltip-arrow"></div>
                                <div class="tooltip-inner">
                                    <span>Only available in<br> Unlimited version</span>
                                    <a class="ars-btn" href="{{ route('apps.upgrade') }}">Upgrade to Unlimited
                                        version</a>
                                </div>
                            </div>
                        @endIf
                    </div>
                    <div class="popular-block {{ (empty($shopPlanInfo['rating_point']) or !in_array(15,$shopPlanInfo['rating_point']) ) ? 'disabled' : '' }}">
                        <label class="ars-radio" for="rating-point-15">
                            <input id="rating-point-15" type="radio" name="rating_point"
                                   value="15" {{ !empty($settings['rating_point'] && $settings['rating_point'] == 15) ?'checked' :'' }}>
                            <span class="icon-block-wrap">
                            <span class="icon-block">
                                <img src="{{ URL::asset('images/backend/rating-point-15.png') }}" alt="">
                            </span>
                            <span class="ars-checked"></span>
                        </span>
                        </label>

                        @if(empty($shopPlanInfo['rating_point']) or !in_array(15,$shopPlanInfo['rating_point']))
                            <div class="tooltip fade right in" role="tooltip">
                                <div class="tooltip-arrow"></div>
                                <div class="tooltip-inner">
                                    <span>Only available in<br> Unlimited version</span>
                                    <a class="ars-btn" href="{{ route('apps.upgrade') }}">Upgrade to Unlimited
                                        version</a>
                                </div>
                            </div>
                        @endIf
                    </div>

                    <div class="popular-block {{ (empty($shopPlanInfo['rating_point']) or !in_array(16,$shopPlanInfo['rating_point']) ) ? 'disabled' : '' }}">
                        <label class="ars-radio" for="rating-point-16">
                            <input id="rating-point-16" type="radio" name="rating_point"
                                   value="16" {{ !empty($settings['rating_point'] && $settings['rating_point'] == 16) ?'checked' :'' }}>
                            <span class="icon-block-wrap">
                            <span class="icon-block">
                                <img src="{{ URL::asset('images/backend/rating-point-16.png') }}" alt="">
                            </span>
                            <span class="ars-checked"></span>
                        </span>
                        </label>

                        @if(empty($shopPlanInfo['rating_point']) or !in_array(16,$shopPlanInfo['rating_point']))
                            <div class="tooltip fade right in" role="tooltip">
                                <div class="tooltip-arrow"></div>
                                <div class="tooltip-inner">
                                    <span>Only available in<br> Unlimited version</span>
                                    <a class="ars-btn" href="{{ route('apps.upgrade') }}">Upgrade to Unlimited
                                        version</a>
                                </div>
                            </div>
                        @endIf
                    </div>

                    <div class="popular-block {{ (empty($shopPlanInfo['rating_point']) or !in_array(17,$shopPlanInfo['rating_point']) ) ? 'disabled' : '' }}">
                        <label class="ars-radio" for="rating-point-17">
                            <input id="rating-point-17" type="radio" name="rating_point"
                                   value="17" {{ !empty($settings['rating_point'] && $settings['rating_point'] == 17) ?'checked' :'' }}>
                            <span class="icon-block-wrap">
                            <span class="icon-block">
                                <img src="{{ URL::asset('images/backend/rating-point-17.png') }}" alt="">
                            </span>
                            <span class="ars-checked"></span>
                        </span>
                        </label>

                        @if(empty($shopPlanInfo['rating_point']) or !in_array(17,$shopPlanInfo['rating_point']))
                            <div class="tooltip fade right in" role="tooltip">
                                <div class="tooltip-arrow"></div>
                                <div class="tooltip-inner">
                                    <span>Only available in<br> Unlimited version</span>
                                    <a class="ars-btn" href="{{ route('apps.upgrade') }}">Upgrade to Unlimited
                                        version</a>
                                </div>
                            </div>
                        @endIf
                    </div>

                </div>
                <h3>2. Rating point</h3>
                <div class="popular-block-wrap">
                    <span class="popular-block-title">Popular</span>
                    <div class="popular-block">
                        <label class="ars-radio" for="rating-card-1">
                            <input id="rating-card-1" type="radio" name="rating_card"
                                   value="1" {{ !empty($settings['rating_card'] && $settings['rating_card'] == 1) ?'checked' :'' }}>
                            <span class="icon-block-wrap">
                            <span class="icon-block">
                                <img src="{{ URL::asset('images/backend/rating-card-1.png') }}" alt="">
                            </span>
                            <span class="ars-checked"></span>
                        </span>
                        </label>
                    </div>
                    <div class="popular-block {{ (empty($shopPlanInfo['rating_card']) or !in_array(2,$shopPlanInfo['rating_card']) ) ? 'disabled' : '' }}">
                        <label class="ars-radio" for="rating-card-2">
                            <input id="rating-card-2" type="radio" name="rating_card"
                                   value="2" {{ !empty($settings['rating_card'] && $settings['rating_card'] == 2) ?'checked' :'' }}>
                            <span class="icon-block-wrap">
                            <span class="icon-block">
                                <img src="{{ URL::asset('images/backend/rating-card-2.png') }}" alt="">
                            </span>
                            <span class="ars-checked"></span>
                        </span>
                        </label>
                    </div>
                </div>
                <div class="popular-block-wrap">
                    <span class="popular-block-title">Season</span>
                    <div class="popular-block {{ (empty($shopPlanInfo['rating_card']) or !in_array(3,$shopPlanInfo['rating_card']) ) ? 'disabled' : '' }}">
                        <label class="ars-radio" for="rating-card-3">
                            <input id="rating-card-3" type="radio" name="rating_card"
                                   value="3" {{ !empty($settings['rating_card'] && $settings['rating_card'] == 3) ?'checked' :'' }}>
                            <span class="icon-block-wrap">
                            <span class="icon-block">
                                <img src="{{ URL::asset('images/backend/rating-card-3.png') }}" alt="">
                            </span>
                            <span class="ars-checked"></span>
                        </span>
                        </label>

                        @if(empty($shopPlanInfo['rating_card']) or !in_array(3,$shopPlanInfo['rating_card']))
                            <div class="tooltip fade right in" role="tooltip">
                                <div class="tooltip-arrow"></div>
                                <div class="tooltip-inner">
                                    <span>Only available in<br> Unlimited version</span>
                                    <a class="ars-btn" href="{{ route('apps.upgrade') }}">Upgrade to Unlimited
                                        version</a>
                                </div>
                            </div>
                        @endIf
                    </div>
                    <div class="popular-block {{ (empty($shopPlanInfo['rating_card']) or !in_array(4,$shopPlanInfo['rating_card']) ) ? 'disabled' : '' }}">
                        <label class="ars-radio" for="rating-card-4">
                            <input id="rating-card-4" type="radio" name="rating_card"
                                   value="4" {{ !empty($settings['rating_card'] && $settings['rating_card'] == 4) ?'checked' :'' }}>
                            <span class="icon-block-wrap">
                            <span class="icon-block">
                                <img src="{{ URL::asset('images/backend/rating-card-4.png') }}" alt="">
                            </span>
                            <span class="ars-checked"></span>
                        </span>
                        </label>

                        @if(empty($shopPlanInfo['rating_card']) or !in_array(4,$shopPlanInfo['rating_card']))
                            <div class="tooltip fade right in" role="tooltip">
                                <div class="tooltip-arrow"></div>
                                <div class="tooltip-inner">
                                    <span>Only available in<br> Unlimited version</span>
                                    <a class="ars-btn" href="{{ route('apps.upgrade') }}">Upgrade to Unlimited
                                        version</a>
                                </div>
                            </div>
                        @endIf
                    </div>
                    <div class="popular-block {{ (empty($shopPlanInfo['rating_card']) or !in_array(5,$shopPlanInfo['rating_card']) ) ? 'disabled' : '' }}">
                        <label class="ars-radio" for="rating-card-5">
                            <input id="rating-card-5" type="radio" name="rating_card"
                                   value="5" {{ !empty($settings['rating_card'] && $settings['rating_card'] == 5) ?'checked' :'' }}>
                            <span class="icon-block-wrap">
                            <span class="icon-block">
                                <img src="{{ URL::asset('images/backend/rating-card-5.png') }}" alt="">
                            </span>
                            <span class="ars-checked"></span>
                        </span>
                        </label>

                        @if(empty($shopPlanInfo['rating_card']) or !in_array(5,$shopPlanInfo['rating_card']))
                            <div class="tooltip fade right in" role="tooltip">
                                <div class="tooltip-arrow"></div>
                                <div class="tooltip-inner">
                                    <span>Only available in<br> Unlimited version</span>
                                    <a class="ars-btn" href="{{ route('apps.upgrade') }}">Upgrade to Unlimited
                                        version</a>
                                </div>
                            </div>
                        @endIf
                    </div>

                    <div class="popular-block {{ (empty($shopPlanInfo['rating_card']) or !in_array(6,$shopPlanInfo['rating_card']) ) ? 'disabled' : '' }}">
                        <label class="ars-radio" for="rating-card-6">
                            <input id="rating-card-6" type="radio" name="rating_card"
                                   value="6" {{ !empty($settings['rating_card'] && $settings['rating_card'] == 6) ?'checked' :'' }}>
                            <span class="icon-block-wrap">
                            <span class="icon-block">
                                <img src="{{ URL::asset('images/backend/rating-card-6.png') }}" alt="">
                            </span>
                            <span class="ars-checked"></span>
                        </span>
                        </label>

                        @if(empty($shopPlanInfo['rating_card']) or !in_array(6,$shopPlanInfo['rating_card']))
                            <div class="tooltip fade right in" role="tooltip">
                                <div class="tooltip-arrow"></div>
                                <div class="tooltip-inner">
                                    <span>Only available in<br> Unlimited version</span>
                                    <a class="ars-btn" href="{{ route('apps.upgrade') }}">Upgrade to Unlimited
                                        version</a>
                                </div>
                            </div>
                        @endIf
                    </div>

                    <div class="popular-block {{ (empty($shopPlanInfo['rating_card']) or !in_array(7,$shopPlanInfo['rating_card']) ) ? 'disabled' : '' }}">
                        <label class="ars-radio" for="rating-card-7">
                            <input id="rating-card-7" type="radio" name="rating_card"
                                   value="7" {{ !empty($settings['rating_card'] && $settings['rating_card'] == 7) ?'checked' :'' }}>
                            <span class="icon-block-wrap">
                            <span class="icon-block">
                                <img src="{{ URL::asset('images/backend/rating-card-7.png') }}" alt="">
                            </span>
                            <span class="ars-checked"></span>
                        </span>
                        </label>

                        @if(empty($shopPlanInfo['rating_card']) or !in_array(7,$shopPlanInfo['rating_card']))
                            <div class="tooltip fade right in" role="tooltip">
                                <div class="tooltip-arrow"></div>
                                <div class="tooltip-inner">
                                    <span>Only available in<br> Unlimited version</span>
                                    <a class="ars-btn" href="{{ route('apps.upgrade') }}">Upgrade to Unlimited
                                        version</a>
                                </div>
                            </div>
                        @endIf
                    </div>

                    <div class="popular-block {{ (empty($shopPlanInfo['rating_card']) or !in_array(8,$shopPlanInfo['rating_card']) ) ? 'disabled' : '' }}">
                        <label class="ars-radio" for="rating-card-8">
                            <input id="rating-card-8" type="radio" name="rating_card"
                                   value="8" {{ !empty($settings['rating_card'] && $settings['rating_card'] == 8) ?'checked' :'' }}>
                            <span class="icon-block-wrap">
                            <span class="icon-block">
                                <img src="{{ URL::asset('images/backend/rating-card-8.png') }}" alt="">
                            </span>
                            <span class="ars-checked"></span>
                        </span>
                        </label>

                        @if(empty($shopPlanInfo['rating_card']) or !in_array(8,$shopPlanInfo['rating_card']))
                            <div class="tooltip fade right in" role="tooltip">
                                <div class="tooltip-arrow"></div>
                                <div class="tooltip-inner">
                                    <span>Only available in<br> Unlimited version</span>
                                    <a class="ars-btn" href="{{ route('apps.upgrade') }}">Upgrade to Unlimited
                                        version</a>
                                </div>
                            </div>
                        @endIf
                    </div>

                </div>
                <div class="theme-store-btn-wrap">
                    @if($shopPlanInfo['name'] == 'free')
                        <button class="ars-submit-btn" type="button">SAVE SETTINGS</button>
                    @else
                        <button class="ars-submit-btn">SAVE SETTINGS</button>
                    @endIf
                </div>
            </div>
        </div>

        <input type="hidden" value="{{ csrf_token() }}" name="_token">
    </form>
@endsection
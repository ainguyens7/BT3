@extends('layout.dashboard', ['page_title' => 'General Settings'])
@section('styles')
@endsection
@section('breadcrumb')
    @include('partials/breadcrumb', ['breadcrumb_link' => 'General Settings'])
@endsection
@section('body_content')
    <form id="form_settings" action="" method="post" novalidate>
        <div class="clearfix"></div>
        <div class="wrapper-space wrapper-space--45 bg-white general-settings-page">
            <h1 class="ali-title-normal mar-t-0 mar-b-50 color-dark-blue">{{ __('settings.title') }}</h1>
            <h2 class="color-dark-blue fz17 fw-bold mar-t-0 mar-b-15">1. {{ __('settings.title_0') }}</h2>
            <div class="mar-b-30">
                <label class="wrap-switch-text">
                    <label class="wrap-switch">
                        <input id="active-frontend" type="checkbox" name="active_frontend" class="switch-input" value="1"
                                {{ !empty($settings['active_frontend']) ? 'checked' : ''}}>
                        <span class="switch-label" data-on="On" data-off="Off"></span>
                        <span class="switch-handle"></span>
                    </label>
                </label>
            </div>

            <h2 class="mar-t-0 mar-b-25 color-dark-blue fz17 fw-bold">2. {{ __('settings.title_1') }}</h2>
            <div class="row">
                <div class="col-lg-3 col-sm-6 mar-b-10 mb-md-20">
                    <h3 class="color-dark-blue fz15 fw-bold mar-b-20 mar-t-0">{{ __('settings.label_only_star') }}</h3>
                    <label class="wrap-custom-box fw500 color-dark-blue mar-b-15"> {!! __('settings.label_star',['star' => 4]) !!}
                        <input id="checkbox-review-5-star"name="setting[get_only_star][]"
                               type="checkbox" value="4"
                                {{ ( !empty($settings['setting']['get_only_star']) && in_array(4,$settings['setting']['get_only_star']) ) ? 'checked' : '' }}>
                        <span class="checkmark-ckb"></span>
                    </label>

                    <label class="wrap-custom-box fw500 color-dark-blue"> {!! __('settings.label_star',['star' => 5]) !!}
                        <input id="checkbox-review-4-star" name="setting[get_only_star][]"
                               type="checkbox" value="5"
                                {{ ( !empty($settings['setting']['get_only_star']) && in_array(5,$settings['setting']['get_only_star']) ) ? 'checked' : '' }}>
                        <span class="checkmark-ckb"></span>
                    </label>
                </div>

                <div class="col-lg-3 col-sm-6 mar-b-10 mb-md-20">
                    <h3 class="color-dark-blue fz15 fw-bold mar-b-20 mar-t-0">{{ __('settings.label_picture_option') }}</h3>
                    <label class="wrap-custom-box fw500 color-dark-blue mar-b-15"> {!! __('settings.label_review_with_picture') !!}
                        <input id="review-with-picture" name="setting[get_only_picture][]" type="checkbox" value="true" {{ in_array("true", $pictureOption) ? 'checked' : ''}}>
                        <span class="checkmark-ckb"></span>
                    </label>

                    <label class="wrap-custom-box fw500 color-dark-blue"> {!! __('settings.label_review_without_picture') !!}
                        <input id="review-without-picture" name="setting[get_only_picture][]" type="checkbox" value="false" {{ in_array("false", $pictureOption) ? 'checked' : ''}}>
                        <span class="checkmark-ckb"></span>
                    </label>
                </div>

                <div class="col-lg-3 col-sm-6 mar-b-10 mb-md-20">
                    <h3 class="color-dark-blue fz15 fw-bold mar-b-20 mar-t-0">{{ __('settings.label_content_options') }}</h3>
                    <label class="wrap-custom-box fw500 color-dark-blue mar-b-15"> {!! __('settings.label_review_with_content') !!}
                        <input id="review-with-content" name="setting[get_only_content][]" type="checkbox" value="true" {{ in_array("true", $contentOption) ? 'checked' : ''}}>
                        <span class="checkmark-ckb"></span>
                    </label>

                    <label class="wrap-custom-box fw500 color-dark-blue"> {!! __('settings.label_review_without_content') !!}
                        <input id="review-without-content" name="setting[get_only_content][]" type="checkbox" value="false" {{ in_array("false", $contentOption) ? 'checked' : ''}}>
                        <span class="checkmark-ckb"></span>
                    </label>
                </div>
            </div>

            <div class="fz11 mar-b-25">
                <i>{{ __('settings.default_get_all') }}</i>
            </div>
            
            <h3 class="color-dark-blue fz15 fw-bold mar-b-20 mar-t-0">{{ __('settings.label_translate_info') }} </h3>
            <div class="mar-b-25">
                <div class="box_setting_translate">
                    <label class="wrap-custom-box fw500 color-dark-blue mar-r-10">
                        <input id="checkbox-translate-english" name="setting[translate_reviews]" value="1" type="checkbox"  {{ ! empty($settings['setting']['translate_reviews']) ? 'checked' : '' }} >
                        <span class="checkmark-ckb"></span>
                    </label>
                </div>

                <div class="box_setting_translate">
                    <select name="setting[language_translate_reviews]" class="form-control select2 unsearch">
                        @foreach(config('ali_languages_site') as $lg_code => $lg_name)
                            <option value="{{ $lg_code }}"
                            {{ (!empty($settings['setting']['language_translate_reviews']) && ($settings['setting']['language_translate_reviews'] == $lg_code)) ? 'selected' : '' }}>{{ $lg_name }}</option>
                        @endForeach
                    </select>
                </div>
                
                <div class="box_setting_translate mar-l-5">
                    {!!  __('settings.label_translate_english') !!} <img src="{{ cdn('images/icons/help-icon.png') }}" data-toggle="tooltip" title="This might not work as expected due to AliExpress translation system." style="margin-left: 8px;">
                </div>
                
                <div class="fz11 mar-b-25 mar-t-10">
                    <i>{{ __('settings.aliexpress_change_cookie') }}</i>
                </div>
            </div>

            <div class="row">
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-lg-4 col-sm-12">
                            <h3 class="color-dark-blue fz15 fw-bold mar-b-20 mar-t-0"> {{ __('settings.label_max_number_review') }}</h3>
                            <div class="form-group mar-b-40 mb-md-25">
                                <input type="number" class="form-control fw600 color-dark-blue" name="setting[get_max_number_review]" value="{{ !empty($settings['setting']['get_max_number_review']) ? $settings['setting']['get_max_number_review'] : 200 }}">
                                <p class="style-error mar-t-5" id="get_max_number_review_error"></p>
                            </div>
                            <h3 class="color-dark-blue fz15 fw-bold mar-b-20 mar-t-0"> {{ __('settings.label_language_review') }}</h3>
                            <div class="form-group mar-b-40 mb-md-25">

                                <select name="setting[country_get_review][]" class="form-control multiselect-search" multiple="multiple">
                                    @foreach ($allCountryCode as $key => $value)
                                        <option value="{{ $key }}" {{  (  (! empty( $settings['setting']['country_get_review'] ) && in_array( $key, $settings['setting']['country_get_review'] ) ))  ? 'selected' : '' }}>{{ $value }} ({{ $key }})</option>
                                    @endforeach
                                </select>
                                <p class="style-error mar-t-5" id="country_get_review_error"></p>
                            </div>
                        </div>

                        <div class="col-lg-6 col-lg-offset-2 col-sm-12 mb-md-25">
                            <div class="rel-tooltip"
                                 place-tooltip="center"
                                 @if(!$shopPlanInfo['except_keyword'])
                                 data-tooltip="{!! __('commons.tooltip_paid') !!}" @endif>

                                <div class="max-import-setting except-keyword-setting mar-b-40
                                        @if(!$shopPlanInfo['except_keyword']) only_available @endif">
                                    <h3 class="color-dark-blue fz15 fw-bold mar-b-20 mar-t-0 d-inline-block">
                                        {{ __('settings.label_except_keyword') }}
                                        <img src="{{ cdn('images/icons/help-icon.png') }}" class="mar-l-5" data-toggle="tooltip" title="Add new keyword by pressing Enter.">
                                    </h3>

                                    <div class="ars-field" style="width: 100%">
                                        @if($shopPlanInfo['except_keyword'])
                                            <textarea  class="form-control tagsinput" placeholder="{{__('settings.enter_key_word')}}"
                                                       name="setting[except_keyword]">{{ !empty($settings['setting']['except_keyword']) ? $settings['setting']['except_keyword'] : '' }}</textarea>
                                        @else
                                            <textarea class="form-control tagsinput ars-checked" readonly style="background: #d2dce8" placeholder="{{__('settings.enter_key_word')}}"></textarea>
                                        @endIf
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="col-lg-6 col-sm-12 mb-md-25">
                            <h3 class="color-dark-blue fz15 fw-bold mar-b-20 mar-t-0" style="white-space: nowrap">
                                {{ __('settings.label_generate_customer_name_by_country') }} &nbsp;
                            </h3>
                            <div class="form-group mar-b-40 mb-md-25">
                                <label class="wrap-custom-box fw-normal color-dark-blue mar-b-20">{{__('settings.text1_generate_customer_name_by_country')}}
                                    <input type="radio" value="0" name="setting[is_local_name]" {{ empty($settings['setting']['is_local_name']) ? 'checked' : ''}}>
                                    <span class="checkmark-rdb"></span>
                                </label>

                                <label style="display: block" class="wrap-custom-box fw-normal color-dark-blue mar-b-5" >{!! __('settings.text2_generate_customer_name_by_country') !!}
                                    <input type="radio" value="1" name="setting[is_local_name]" {{ !empty($settings['setting']['is_local_name']) ? 'checked' : ''}}>
                                    <span class="checkmark-rdb"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <h3 class="color-dark-blue fz15 fw-bold mar-b-20 mar-t-0">
                                {{ __('settings.label_customer_gender_rate') }} &nbsp;
                            </h3>
                            <div class="form-group mar-b-40 mb-md-25 is_gender_percent_box">
                                <div class="row" style="max-width: 322px;">
                                    <div class="col-lg-6 col-xs-6">
                                        <input type="number" class="form-control gender_name_percent_input" placeholder="" name="setting[male_name_percent]"
                                               value="{{ (!empty($settings['setting']['male_name_percent']) && is_numeric($settings['setting']['male_name_percent'])) ? intval($settings['setting']['male_name_percent']) : 0  }}" min="0" step="1" max="100" pattern="\d*"
                                               onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                        % Male
                                    </div>

                                    <div class="col-lg-6 col-xs-6">
                                        <input type="number" readonly class="form-control gender_name_percent_input" placeholder="" name="setting[female_name_percent]"
                                               value="{{ (!empty($settings['setting']['female_name_percent']) && is_numeric($settings['setting']['female_name_percent'])) ? intval($settings['setting']['female_name_percent']) : 0 }}">
                                        % Female
                                    </div>

                                    <div class="col-xs-12 error">
                                        <div class="gender_percent_error" style="margin-top: 5px"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <h2 class="color-dark-blue fz17 fw-bold mar-b-20 mar-t-0">3. Auto Publish Review</h2>
                        </div>

                        <div class="col-lg-12">
                            <label class="wrap-custom-box fw500 fz13 color-dark-blue mar-b-35">
                                <span>{{ __('settings.title_22') }} </span>
                                <input id="approve-review-manually" type="checkbox" name="approve_review" value="1"
                                        {{ !empty($settings['approve_review']) ? 'checked' : ''}}>
                                <span class="checkmark-ckb"></span>
                            </label>

                            <div class="row approve-review-stars mar-b-20" style="{{ !empty($settings['approve_review']) ? 'display:block' :'' }}; max-width: 530px;">
                                <div class="col-xs-4 col-sm-2 col-md-2">
                                    <label class="wrap-custom-box fw500 color-dark-blue">
                                        <span class="item">1 <span class="alr-icon-star" ></span></span>
                                        <input type="checkbox" name="setting[approve_review_stars][]" value="1"
                                                {{ in_array(1,$settings['setting']['approve_review_stars']) ? 'checked' : ''}}>
                                        <span class="checkmark-ckb"></span>
                                    </label>
                                </div>

                                <div class="col-xs-4 col-sm-2 col-md-2">
                                    <label class="wrap-custom-box fw500 color-dark-blue">
                                        <span class="item">2 <span class="alr-icon-star" ></span></span>
                                        <input type="checkbox" name="setting[approve_review_stars][]" value="2"
                                                {{ in_array(2,$settings['setting']['approve_review_stars']) ? 'checked' : ''}}>
                                        <span class="checkmark-ckb"></span>
                                    </label>
                                </div>

                                <div class="col-xs-4 col-sm-2 col-md-2">
                                    <label class="wrap-custom-box fw500 color-dark-blue">
                                        <span class="item">3 <span class="alr-icon-star" ></span></span>
                                        <input type="checkbox" name="setting[approve_review_stars][]" value="3"
                                                {{ in_array(3,$settings['setting']['approve_review_stars']) ? 'checked' : ''}}>
                                        <span class="checkmark-ckb"></span>
                                    </label>
                                </div>

                                <div class="col-xs-4 col-sm-2 col-md-2">
                                    <label class="wrap-custom-box fw500 color-dark-blue">
                                        <span class="item">4 <span class="alr-icon-star" ></span></span>
                                        <input type="checkbox" name="setting[approve_review_stars][]" value="4"
                                                {{ in_array(4,$settings['setting']['approve_review_stars']) ? 'checked' : ''}}>
                                        <span class="checkmark-ckb"></span>
                                    </label>
                                </div>

                                <div class="col-xs-4 col-sm-2 col-md-2">
                                    <label class="wrap-custom-box fw500 color-dark-blue">
                                        <span class="item">5 <span class="alr-icon-star" ></span></span>
                                        <input type="checkbox" name="setting[approve_review_stars][]" value="5"
                                                {{ in_array(5,$settings['setting']['approve_review_stars']) ? 'checked' : ''}}>
                                        <span class="checkmark-ckb"></span>
                                    </label>
                                </div>

                                <div class="col-md-12">
                                    <p class="style-error mar-t-5" id="approve-review-manually-error"></p>
                                </div>
                            </div>
                        </div>

                        <div 
                            class="col-lg-12 rel-tooltip"
                            place-tooltip="center"
                            @if ($shopPlanInfo['name'] != 'unlimited')
                                data-tooltip="{!! __('commons.tooltip_unlimited') !!}"   
                            @endif
                        >
                            <div 
                                class="max-import-setting except-keyword-setting 
                                @if ($shopPlanInfo['name'] != 'unlimited')
                                only_available
                                @endif
                            ">
                                <h2 class="color-dark-blue fz17 fw-bold mar-b-20 mar-t-0">
                                    <span>
                                        4. {{ __('settings.active_your_affiliate') }}
                                    </span>
                                </h2>

                                <div class="general-setting-block affiliate-settings {{ $shopPlanInfo['name'] != 'unlimited' ? 'overlay-affiliate' : '' }}" style="margin-bottom: 50px">
                                    <div class="ars-radio-container mar-b-20">
                                        <label class="wrap-custom-box fw-normal color-dark-blue mar-b-5">{{__('settings.label_do_not_user_any_affiliate_program')}}
                                            <input type="radio" value="none" name="setting[affiliate_program]" {{ $affiliateProgram == 'none' ? 'checked' : ''}} {{ $shopPlanInfo['name'] != 'unlimited' ? 'disabled': '' }}>
                                            <span class="checkmark-rdb"></span>
                                        </label>
                                    </div>

                                    <div class="affiliate-aliexpress aff-block {{ $affiliateProgram == 'aliexpress' ? 'in' : ''}}">
                                        <div class="ars-radio-container mar-b-10">
                                            <label class="wrap-custom-box fw-normal color-dark-blue" for="affiliate_program_aliexpress"> {{__('settings.label_program_credentials')}}
                                                <input id="affiliate_program_aliexpress" type="radio" value="aliexpress"  name="setting[affiliate_program]" {{ $affiliateProgram == 'aliexpress' ? 'checked' : ''}} {{ $shopPlanInfo['name'] != 'unlimited' ? 'disabled': '' }}>
                                                <span class="checkmark-rdb"></span>
                                            </label>
                                        </div>

                                        <div class="mar-b-10 aff-content">
                                            <div class="col-md-6">
                                                <label for="affiliate_program_aliexpress_api_key">{{__('settings.label_api_key')}}</label>
                                                <div class="ars-field">
                                                    <input type="text" id="affiliate_program_aliexpress_api_key" name="setting[affiliate_aliexpress][api_key]" class="valid form-control" value="{{ !empty($affiliateProAliexpress) ? $affiliateProAliexpress->api_key : '' }}" aria-invalid="false" {{ $shopPlanInfo['name'] != 'unlimited' ? 'disabled': '' }}>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="affiliate_program_aliexpress_tracking_id">{{__('settings.label_tracking_id')}} </label>
                                                <div class="ars-field">
                                                    <input type="text" id="affiliate_program_aliexpress_tracking_id" name="setting[affiliate_aliexpress][tracking_id]" class="valid form-control" value="{{ !empty($affiliateProAliexpress) ? $affiliateProAliexpress->tracking_id : '' }}" aria-invalid="false" {{ $shopPlanInfo['name'] != 'unlimited' ? 'disabled': '' }}>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="affiliate-admitad aff-block {{ $affiliateProgram == 'admitad' ? 'in' : ''}}" style="margin-top: 30px">
                                        <div class="ars-radio-container mar-b-10">
                                            <label class="wrap-custom-box fw-normal color-dark-blue">{{__('settings.label_admitad_affiliate_id')}}
                                                <input id="affiliate_program_admitad" type="radio" value="admitad" name="setting[affiliate_program]" {{ $affiliateProgram == 'admitad' ? 'checked' : ''}} {{ $shopPlanInfo['name'] != 'unlimited' ? 'disabled': '' }}>
                                                <span class="checkmark-rdb"></span>
                                            </label>
                                        </div>
                                        
                                        <div class="col-md-6 aff-content">
                                            <label for="affiliate_program_admitad_affiliate_id">{{__('settings.label_affiliate_id')}}</label>
                                            <div class="ars-field">
                                                <input type="text" id="affiliate_program_admitad_affiliate_id" name="setting[affiliate_admitad][affiliate_id]" class="valid form-control" value="{{ !empty($affilaiteProAdmitad)  ? $affilaiteProAdmitad->affiliate_id : '' }}" aria-invalid="false" {{ $shopPlanInfo['name'] != 'unlimited' ? 'disabled': '' }}>
                                            </div>
                                        </div>

                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-align-lg-c">
                <button type="submit" class="button button--primary mar-r-15">Save Settings</button>
                {{csrf_field()}}
            </div>
        </div>
    </form>
    @include('settings/notify_save_success')
@endsection

@extends('layout.dashboard', ['page_title' => 'Theme Settings'])
@section('styles')
@endsection
@section('breadcrumb')
    @include('partials/breadcrumb', ['breadcrumb_link' => 'Theme Settings'])
@endsection
@section('body_content')
    <div class="clearfix"></div>
    @if(session('error'))
        <div class="alert alert-warning" role="alert">
            {{ session('error') }}
        </div>
    @endif
    <div class="wrapper-space wrapper-space--45 bg-white theme-settings-page pad-b-0">
        <form method="post" novalidate id="form-theme-settings">
            <h1 class="ali-title-normal mar-t-0 mar-b-10 color-dark-blue">{{__('settings.title_themes_setting')}}</h1>
            <p class="ali-body-medium mar-b-50">{{__('settings.description_themes_setting')}}</p>
            <div class="row">
                <div class="col-lg-6 comment-form-layout">
                    <h2 class="color-dark-blue fz17 fw700 mar-t-0 mar-b-15 mar-b-35">1. {{__('settings.title_2')}}</h2>

                    <div class="row mar-t-10" style="margin-bottom: 12px;">
                        <div class="col-md-4">
                            <h3 class="fz15 color-dark-blue fw700 mar-t-5">{{__('settings.label_display_rate_list')}}</h3>
                        </div>

                        <div class="col-md-2 padl-0 mar-b-10-lg">
                            <label class="wrap-switch-text">
                                <label class="wrap-switch">
                                    <input id="active-display_rate_list" type="checkbox"  name="setting[display_rate_list]" class="switch-input" value="1"
                                    {{ !empty($settings['setting']['display_rate_list']) ? 'checked' : ''}}>
                                    <span class="switch-label" data-on="On" data-off="Off"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            </label>
                        </div>

                        <div class="col-md-3 pad-r-0 mar-b-10-lg">
                            <h3 class="fz15 color-dark-blue fw700  mar-t-5">{{__('settings.label_advance_sort')}}</h3>
                        </div>

                        <div class="col-md-2 padl-0">
                            <label class="wrap-switch-text">
                                <label class="wrap-switch">
                                    <input type="checkbox" name="setting[display_advance_sort]" class="switch-input" value="1"
                                    {{ !empty($settings['setting']['display_advance_sort']) ? 'checked' : ''}}>
                                    <span class="switch-label" data-on="On" data-off="Off"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            </label>
                        </div>
                    </div>

                    <div class="row mar-b-20 flex-align-center-md">
                        <div class="col-md-4 mar-b-5-lg">
                            <h3 class="fz15 color-dark-blue fw700 mar-0">{{__('settings.label_date_display')}}</h3>
                        </div>
                        <div class="col-md-2 padl-0 mar-b-10-lg">
                            <label class="wrap-switch-text pad-t-0">
                                <label class="wrap-switch">
                                    <input id="active_date_format" type="checkbox"  name="setting[active_date_format]" class="switch-input" value="1"
                                            {{ !empty($settings['setting']['active_date_format']) ? 'checked' : ''}}>
                                    <span class="switch-label" data-on="On" data-off="Off"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            </label>
                        </div>

                        <div class="col-md-3 mar-b-10-lg">
                            <h3 class="fz15 color-dark-blue fw700 mar-0">{{__('settings.label_date_format')}}</h3>
                        </div>
                        <div class="col-md-3 padl-0 mar-b-10-lg">
                            <select name="setting[date_format]" class="form-control select2 unsearch" >
                                @foreach(config('date_format.setting') as $item)
                                    <option {{(isset($settings['setting']['date_format'])&& ($settings['setting']['date_format'] === $item))? 'selected' : '' }} value="{{$item}}">{{date($item)}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <h3 class="fz15 color-dark-blue fw700 mar-b-20">{{__('settings.title_21')}}</h3>
                    <div class="row">
                        <div class="col-md-5">
                            <label class="wrap-custom-box fw-normal mar-b-20 color-dark-blue"> {{__('settings.label_section_show_country')}}
                                <input id="checkbox-display-country"
                                       type="checkbox" name="setting[section_show][]" value="country"
                                        {{ ( !empty($settings['setting']['section_show']) and in_array('country',$settings['setting']['section_show']) ) ? 'checked' : ''}}>
                                <span class="checkmark-ckb"></span>
                            </label>
                        </div>

                        <div class="col-md-7">
                            <label class="wrap-custom-box fw-normal mar-b-20"> {{__('settings.label_section_show_review_image')}}
                                <input id="checkbox-display-review-images" type="checkbox" name="setting[section_show][]"
                                       value="image"
                                        {{ (!empty($settings['setting']['section_show']) && in_array('image',$settings['setting']['section_show']) ) ? 'checked' : ''}}>
                                <span class="checkmark-ckb"></span>
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5">
                            <label class="wrap-custom-box fw-normal mar-b-20"> {{__('settings.label_section_hide_name')}}
                                <input id="checkbox-display-use-name" type="checkbox" name="setting[section_show][]"
                                       value="hide_name"
                                        {{ ( ! empty($settings['setting']['section_show']) && in_array('hide_name',$settings['setting']['section_show']) ) ? 'checked' : ''}}>
                                <span class="checkmark-ckb"></span>
                            </label>
                        </div>
                        <div class="col-md-7">
                            <label class="wrap-custom-box fw-normal mar-b-20"> {{__('settings.label_section_show_avatar')}}
                                <input id="checkbox-display-use-avatar" type="checkbox" name="setting[section_show][]"
                                       value="avatar"
                                        {{ ( ! empty($settings['setting']['section_show']) && in_array('avatar',$settings['setting']['section_show']) ) ? 'checked' : ''}}>
                                <span class="checkmark-ckb"></span>
                            </label>
                        </div>
                    </div>

                    {{-- get_max_number_review --}}
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="fz15 color-dark-blue fw700 mar-b-20 mar-t-10">{{__('settings.label_max_number_per_page')}}</h3>
                            <input type="text" value="{{ !empty($settings['setting']['max_number_per_page']) ? $settings['setting']['max_number_per_page'] : 8 }}" name="setting[max_number_per_page]" class="form-control">
                            <p class="style-error mar-t-5" id="setting[max_number_per_page]-error" style="display: none;">Field must be greater than 0 and less than 20</p>
                        </div>
                    </div>
                    
                    {{-- SORT  --}}
                    <h3 class="fz15 color-dark-blue fw700 mar-b-20 mar-t-35">{{__('settings.title_23')}}</h3>
                    <div class="row mar-b-20">
                        <div class="col-sm-4">
                            <label class="wrap-custom-box fw-normal color-dark-blue"> {!! __('settings.label_sort_date') !!}
                                <input id="sort-date-radio" type="radio" value="sort_by_date" checked
                                       name="setting[sort_reviews]"
                                        {{ ( !empty($settings['setting']['sort_reviews']) and $settings['setting']['sort_reviews'] =='sort_by_date') ? 'checked' : ''}}>
                                <span class="checkmark-rdb"></span>
                            </label>
                        </div>

                        <div class="col-sm-4 padl-0">
                            <label class="wrap-custom-box fw-normal color-dark-blue"> {!! __('settings.label_sort_social') !!}
                                <input id="sort-social-radio" value="sort_by_social" type="radio"
                                       name="setting[sort_reviews]"
                                        {{ ( !empty($settings['setting']['sort_reviews']) and $settings['setting']['sort_reviews'] =='sort_by_social') ? 'checked' : ''}}>
                                <span class="checkmark-rdb"></span>
                            </label>
                        </div>

                        <div class="col-sm-4 padl-0">
                            <label class="wrap-custom-box fw-normal color-dark-blue"> {!! __('settings.label_sort_like_and_dislike') !!}
                                <input id="sort-like-radio" type="radio" value="sort_by_like"
                                       name="setting[sort_reviews]"
                                        {{ ( !empty($settings['setting']['sort_reviews']) and $settings['setting']['sort_reviews'] =='sort_by_like') ? 'checked' : ''}}>
                                <span class="checkmark-rdb"></span>
                            </label>
                        </div>
                    </div>
                    {{-- END: SORT  --}}

                    <hr class="mar-b-30">
                    <div>
                        <h2 class="color-dark-blue fz17 fw700 mar-t-0 mar-b-15 mar-b-35">2. {{__('settings.title_3')}}</h2>
                        <div class="row mar-b-30 mar-b-10-lg">
                            <div class="col-md-3">
                                <label class="pad-t-7 fw500">{{__('settings.label_summary_box')}}</label>
                            </div>

                            <div class="col-md-3 rps-pad-l  mar-b-10-lg">
                                <select name="rating_point"  class="form-control select2 unsearch">
                                    <option  {{ (!empty($settings['rating_point'] == 1 ) ? 'selected' : '')}} value="1"> Square</option>
                                    <option  {{ (!empty($settings['rating_point'] == 2) ? 'selected' : '')}} value="2"> Circle</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="pad-t-7 fw500">{{__('settings.label_box_color')}}</label>
                            </div>

                            <div class="col-md-3 rps-pad-l">
                                <div class="input-group bd-0 colorpicker-init" id="realBoxColor">
                                    <span class="input-group-addon bd-r-0"><i></i></span>
                                    <input name="style_customize[box_color]"  type="text" class="form-control pad-l-0 bd-l-0" value="{{!empty($settings['style_customize']['box_color']) ? $settings['style_customize']['box_color'] : '#ffffff'}}" />
                                </div>
                            </div>
                        </div>
                            
                        <div class="row mar-b-30 mar-b-10-lg">
                            <div class="col-md-3">
                                <label class="pad-t-7 fw500">{{__('settings.label_layout_style')}} </label>
                            </div>

                            <div class="col-md-3 rps-pad-l mar-b-10-lg">
                                <select name="style" class="form-control select2 unsearch">
                                    <option  {{ (!empty($settings['style'] == 2) ? 'selected' : '') }} value="2">Lists</option>
                                    <option  {{ (!empty($settings['style'] == 5) ? 'selected' : '') }} value="5">Grids</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="pad-t-7 fw500">{{__('settings.label_verify_color')}}</label>
                            </div>
                            <div class="col-md-3 rps-pad-l">
                                <div class="input-group colorpicker-init bd-0" id="realVerifyColor">
                                    <span class="input-group-addon bd-r-0"><i></i></span>
                                    <input name="style_customize[verify_color]" type="text" class="form-control pad-l-0 bd-l-0" value="{{!empty($settings['style_customize']['verify_color']) ? $settings['style_customize']['verify_color'] : config('settings.style_customize.verify_color')}}"/>
                                </div>
                            </div>
                        </div>
                        <div class="row mar-b-30 mar-b-10-lg">
                            <div class="col-md-3">
                                <label class="pad-t-7 fw500">{{__('settings.label_icon_style')}}</label>
                            </div>

                            <div class="col-md-3 rps-pad-l mar-b-10-lg">
                                <select name="rating_card" class="form-control select2-view-icon unsearch">
                                    <option {{ (!empty($settings['rating_card'] == 1) ? 'selected' : '')}} value="1" icon="alr-icon-star"></option>
                                    <option {{ (!empty($settings['rating_card'] == 2) ? 'selected' : '')}} value="2" icon="alr-icon-favorite"></option>
                                    <option {{ (!empty($settings['rating_card'] == 3) ? 'selected' : '')}} value="3" icon="alr-icon-unit"></option>
                                    <option {{ (!empty($settings['rating_card'] == 4) ? 'selected' : '')}} value="4" icon="alr-icon-notifications"></option>
                                    <option {{ (!empty($settings['rating_card'] == 5) ? 'selected' : '')}} value="5" icon="alr-icon-like"></option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="pad-t-7 fw500">{{__('settings.label_icon_color')}}</label>
                            </div>

                            <div class="col-md-3 rps-pad-l">
                                <div class="input-group colorpicker-init bd-0" id="realIconColor">
                                    <span class="input-group-addon bd-r-0"><i></i></span>
                                    <input  name="style_customize[icon_color]" type="text" class="form-control pad-l-0 bd-l-0" value="{{!empty($settings['style_customize']['icon_color']) ? $settings['style_customize']['icon_color'] : config('settings.style_customize.icon_color')}}" />
                                </div>
                            </div>
                        </div>

                        <div class="row mar-b-45">
                            <div class="col-md-3">
                                <label class="pad-t-7 fw500">{{__('settings.label_avatar_style')}}</label>
                            </div>

                            <div class="col-md-3 rps-pad-l">
                                <select  name="style_customize[avatar]" class="form-control select2 unsearch" id="realAvatar">
                                    <option {{(isset($settings['style_customize']['avatar'])&& ($settings['style_customize']['avatar'] =='square'))? 'selected' : '' }} value="square">Square</option>
                                    <option {{(isset($settings['style_customize']['avatar'])  && ($settings['style_customize']['avatar'] == 'circle')  ) ? 'selected' : '' }} value="circle">Circle</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="edit-css">
                        <h2 class="color-dark-blue fz17 fw700 mar-t-0 mar-b-15 mar-b-35">3. {{__('settings.title_5')}}</h2>
                        <div class="row flex-align-center-md mar-b-20">
                            <div class="col-xs-3">
                                <h3 class="fz15 color-dark-blue fw700 mar-0">Active CSS</h3>
                            </div>

                            <div class="col-xs-3 padl-0">
                                <label class="wrap-switch-text">
                                    <label class="wrap-switch">
                                        <input value="1" name="is_code_css" type="checkbox" {{ !empty($settings['is_code_css']) ? 'checked' :'' }} class="switch-input"/>
                                        <span class="switch-label" data-on="On" data-off="Off"></span>
                                        <span class="switch-handle"></span>
                                    </label>
                                </label>
                            </div>
                        </div>

                        <div class="row mar-b-30">
                            <div class="col-xs-12">
                                <div class="{{ !empty($settings['is_code_css']) ? 'active' : '' }}">
                                    <textarea id="style-advance-mode" name="code_css" class="form-control resize-v mirror-code" rows="3" readonly>{{ $settings['code_css'] }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mar-b-30">
                        <div class="col-md-12 text-align-lg-c">
                            <button type="submit" class="button button--primary mar-r-15">Save Settings</button>
                            <button type="button" class="button button--default resetThemeSetting">Reset To Default</button>
                        </div>
                    </div>
                </div>
                <!-- END: col-left -->

                <div class="col-lg-6 rps-pad-l">
                    <div class="wrapper-preview-real bg-dark-blue">
                        <div class="preview-real__header mar-b-15" style="display: flex; align-items: center;">
                            <label class="fz18"> {{ config('settings')['translate']['label_live_review'] }} </label>
                            <div class="clearfix"></div>
                        </div>

                        <div class="preview-real__content bg-white">

                            <h3 class="fw700 fz20 color-dark-blue mar-t-0 mar-b-25">Customer Reviews</h3>
                            {{-- Summary --}}
                            <div class="alr-summary mar-b-20">
                                <div class="alr-wrap-point">
                                    <div class="alr-point" id="realSummaryBox">4.5</div>
                                </div>
                                <div class="alr-wrap-star">
                                    <div class="alr-star">
                                        <div style="margin-bottom: -5px;">
                                            <input type="hidden" class="alr-rating" data-filled="alr-icon-star" data-empty="alr-icon-star" data-fractions="3" data-fractions="3" data-readonly value="4.5"/>
                                        </div>
                                        <span class="alr-sub">Based on 120 reviews</span>
                                    </div>
                                </div>
                                <button type="button" class="alr-write">Write a review</button>
                                <div class="alr-wrap-count">
                                    <ul class="alr-count-reviews">
                                        <li>
                                            <div class="alr-sum-wrap">
                                                <span class="alr-sum-point">5</span>
                                                <span class="alr-star"><i class="alr-icon-star" style="color: #FFB303;"></i></span>
                                            </div>
                                            <div class="alr-progress-bar-wrap">
                                                <div class="alr-progress-bar">
                                                    <div style="max-width: 95%"></div>
                                                </div>
                                            </div>
                                            <span class="alr-count">(50)</span>
                                        </li>
                                        <li>
                                            <div class="alr-sum-wrap">
                                                <span class="alr-sum-point">4</span>
                                                <span class="alr-star"><i class="alr-icon-star" style="color: #FFB303;"></i></span>
                                            </div>
                                            <div class="alr-progress-bar-wrap">
                                                <div class="alr-progress-bar">
                                                    <div style="max-width: 45%"></div>
                                                </div>
                                            </div>
                                            <span class="alr-count">(32)</span>
                                        </li>
                                        <li>
                                            <div class="alr-sum-wrap">
                                                <span class="alr-sum-point">3</span>
                                                <span class="alr-star"><i class="alr-icon-star" style="color: #FFB303;"></i></span>
                                            </div>
                                            <div class="alr-progress-bar-wrap">
                                                <div class="alr-progress-bar">
                                                    <div style="max-width: 35%"></div>
                                                </div>
                                            </div>
                                            <span class="alr-count">(25)</span>
                                        </li>
                                        <li>
                                            <div class="alr-sum-wrap">
                                                <span class="alr-sum-point">2</span>
                                                <span class="alr-star"><i class="alr-icon-star" style="color: #FFB303;"></i></span>
                                            </div>
                                            <div class="alr-progress-bar-wrap">
                                                <div class="alr-progress-bar">
                                                    <div style="max-width: 15%"></div>
                                                </div>
                                            </div>
                                            <span class="alr-count">(7)</span>
                                        </li>
                                        <li>
                                            <div class="alr-sum-wrap">
                                                <span class="alr-sum-point">1</span>
                                                <span class="alr-star"><i class="alr-icon-star" style="color: #FFB303;"></i></span>
                                            </div>
                                            <div class="alr-progress-bar-wrap">
                                                <div class="alr-progress-bar">
                                                    <div style="max-width: 3%"></div>
                                                </div>
                                            </div>
                                            <span class="alr-count">(1)</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            {{-- END: Summary --}}

                            {{-- <hr> --}}

                            {{-- Advance sort --}}
                            <div class="advance-sort">
                                <span>
                                    <img src="{{ cdn('images/icons/icon-filter.png') }}">
                                </span>
                            </div>
                            {{-- END: Advance sort --}}

                            {{-- reviews --}}
                            <div class="alr-wrap-list-rv list" id="realLayoutStyle">
                                <div class="alr-grid">
                                    {{-- item --}}
                                    <div class="alr-grid-item">
                                        <div class="alr-wrap">
                                            <div class="alr-img">
                                                <img src="{{ cdn('images/theme-setting-page/thumb-1.jpg') }}" alt="">
                                            </div>

                                            <div class="alr-avatar">
                                                <img src="{{ cdn('images/theme-setting-page/theme-setting-user-1.png') }}" img-user="{{ cdn('images/theme-setting-page/theme-setting-user-1.png') }}" img-abstract="{{ cdn('images/avatar/abstract/avatar2.jpg') }}" alt="">
                                                <p class="alr-name">
                                                    <span class="arl-name-guest" name-default="Maximus Bernhard">Maximus Bernhard</span>
                                                    <span class="alr-verified alr-icon-verified"></span>
                                                </p>
                                                <div class="alr-flag">
                                                    <i class="ali-flag-slc gg"></i>
                                                    <span>GG</span>
                                                </div>
                                            </div>

                                            <div class="alr-content">
                                                <div class="alr-rating-wrap format-date" date="2018/05/17">
                                                    <div style="width: 75px;" class="display-rating">
                                                        <input type="hidden" class="alr-rating" data-filled="alr-icon-star" data-empty="alr-icon-star" data-fractions="3" data-readonly value="3"/>
                                                    </div>
                                                    <span class="alr-verified alr-icon-verified"></span>
                                                </div>
                                                <article class="descript">Lorem ipsum dolor sit?</article>
                                                <ul class="alr-thumbnail">
                                                    <li><img src="{{ cdn('images/theme-setting-page/theme-setting-img-1.png') }}"></li>
                                                    <li><img src="{{ cdn('images/theme-setting-page/theme-setting-img-2.png') }}"></li>
                                                    <li><img src="{{ cdn('images/theme-setting-page/theme-setting-img-3.png') }}"></li>
                                                </ul>
                                                <div class="alr-status format-date" date="2018/05/17">
                                                    <span class="alr-like-count">5</span>
                                                    <span class="alr-icon-like active"></span>
                                                    <span class="alr-unlike-count">23</span>
                                                    <span class="alr-icon-unlike"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- END: item --}}

                                    {{-- item --}}
                                    <div class="alr-grid-item">
                                        <div class="alr-wrap">
                                            <div class="alr-img">
                                                <img src="{{ cdn('images/theme-setting-page/thumb-2.jpg') }}" alt="">
                                            </div>

                                            <div class="alr-avatar">
                                                <img src="{{ cdn('images/theme-setting-page/theme-setting-user-2.png') }}" img-user="{{ cdn('images/theme-setting-page/theme-setting-user-1.png') }}" img-abstract="{{ cdn('images/avatar/abstract/avatar5.jpg') }}" alt="">
                                                <p class="alr-name">
                                                    <span class="arl-name-guest" name-default="Maximus Bernhard">Maximus Bernhard</span>
                                                    <span class="alr-verified alr-icon-verified"></span>
                                                </p>
                                                <div class="alr-flag">
                                                    <i class="ali-flag-slc sm"></i>
                                                    <span>SM</span>
                                                </div>
                                            </div>

                                            <div class="alr-content">
                                                <div class="alr-rating-wrap format-date" date="2018/05/17">
                                                    <div style="width: 75px;" class="display-rating">
                                                        <input type="hidden" class="alr-rating" data-filled="alr-icon-star" data-empty="alr-icon-star" data-fractions="3" data-fractions="3" data-readonly value="4"/>
                                                    </div>
                                                    <span class="alr-verified alr-icon-verified"></span>
                                                </div>
                                                <article class="descript">Lorem ipsum dolor sit amet. Lorem ipsum dolor sit?</article>
                                                <div class="alr-status format-date" date="2018/05/17">
                                                    <span class="alr-like-count">5</span>
                                                    <span class="alr-icon-like active"></span>
                                                    <span class="alr-unlike-count">23</span>
                                                    <span class="alr-icon-unlike"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- END: item --}}

                                    {{-- item --}}
                                    <div class="alr-grid-item">
                                        <div class="alr-wrap">
                                            <div class="alr-img">
                                                <img src="{{ cdn('images/theme-setting-page/thumb-1.jpg') }}" alt="">
                                            </div>

                                            <div class="alr-avatar">
                                                <img src="{{ cdn('images/theme-setting-page/theme-setting-user-1.png') }}" img-user="{{ cdn('images/theme-setting-page/theme-setting-user-1.png') }}" img-abstract="{{ cdn('images/avatar/abstract/avatar18.jpg') }}" alt="">
                                                <p class="alr-name">
                                                    <span class="arl-name-guest" name-default="Maximus Bernhard">Maximus Bernhard</span>
                                                    <span class="alr-verified alr-icon-verified"></span>
                                                </p>
                                                <div class="alr-flag">
                                                    <i class="ali-flag-slc zm"></i>
                                                    <span>ZM</span>
                                                </div>
                                            </div>

                                            <div class="alr-content">
                                                <div class="alr-rating-wrap format-date" date="2018/05/17">
                                                    <div style="width: 75px;" class="display-rating">
                                                        <input type="hidden" class="alr-rating" data-filled="alr-icon-star" data-empty="alr-icon-star" data-fractions="3" data-fractions="3" data-readonly value="2.5"/>
                                                    </div>
                                                    <span class="alr-verified alr-icon-verified"></span>
                                                </div>
                                                <article class="descript">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Nisi, vero.</article>
                                                <ul class="alr-thumbnail">
                                                    <li><img src="{{ cdn('images/theme-setting-page/theme-setting-img-1.png') }}"></li>
                                                    <li><img src="{{ cdn('images/theme-setting-page/theme-setting-img-2.png') }}"></li>
                                                    <li><img src="{{ cdn('images/theme-setting-page/theme-setting-img-3.png') }}"></li>
                                                </ul>
                                                <div class="alr-status format-date" date="2018/05/17">
                                                    <span class="alr-like-count">5</span>
                                                    <span class="alr-icon-like active"></span>
                                                    <span class="alr-unlike-count">23</span>
                                                    <span class="alr-icon-unlike"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- END: item --}}
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            {{-- END: reviews --}}
                        </div>
                    </div>
                </div>
                <!-- END: col-right -->
            </div>
            <!-- END: row -->

            <div class="modal_preview_real hidden-lg">
                <button type="button" class="button button--primary button--icon" data-toggle="modal" data-target="#preview-real">
                    <i class="material-icons">brightness_medium</i>
                </button>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="preview-real" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="showCurrentPreviewReal"></div>
                    </div>
                </div>
            </div>
            {!! csrf_field() !!}
        </form>
        @include('settings/modal-confirm-reset-themes-settings')
        @include('settings/notify_save_success')
    </div>
@endsection
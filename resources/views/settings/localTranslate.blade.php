@extends('layout.dashboard', ['page_title' => 'Local Translate'])
@section('styles')
@endsection
@section('breadcrumb')
    @include('partials/breadcrumb', ['breadcrumb_link' => 'Translations'])
@endsection
@section('body_content')
    @if(session('error'))
        <div class="alert alert-warning" role="alert">
            {{ session('error') }}
        </div>
    @endif
    <form id="form_localTranslate" action="" method="post" novalidate>
        <div class="clearfix"></div>
        <div class="wrapper-space wrapper-space--45 bg-white translate-page">
            <h1 class="ali-title-normal mar-t-0 mar-b-10 color-dark-blue">{{ __('settings.title_translate') }}</h1>
            {{-- <p class="ali-body-medium mar-b-20">{{ __('settings.description_translate') }}</p> --}}
            <p class="ali-body-medium mar-b-50"></p>

            <div class="row mar-b-20">
                <div class="col-lg-6">
                    <h2 class="color-dark-blue fz17 fw700 mar-t-0 mar-b-15 mar-b-20">{!! __('settings.label_header_review_count') !!}</h2>
                    <div class="row mar-b-20">
                        <div class="col-lg-3 col-sm-12">
                            <label class="pad-t-7 fw500">{!! __('settings.label_review_count') !!}</label>
                        </div>
                        <div class="col-lg-9">
                            <input type="text" name="translate[text_review_title]"
                                    placeholder="{{ config('settings')['translate']['text_review_title'] }}"
                                    value="{{ !empty($settings['translate']['text_review_title']) ? $settings['translate']['text_review_title']  :'' }}" class="form-control">
                        </div>
                    </div>
                    <div class="row mar-b-20">
                        <div class="col-lg-3 col-sm-12">
                            <label class="pad-t-7 fw500">{!! __('settings.label_review_count_plural') !!}</label>
                        </div>
                        <div class="col-lg-9">
                            <input type="text" name="translate[text_reviews_title]"
                                    placeholder="{{ config('settings')['translate']['text_reviews_title'] }}"
                                    value="{{ !empty($settings['translate']['text_reviews_title']) ? $settings['translate']['text_reviews_title']  :'' }}" class="form-control">
                        </div>
                    </div>

                    <h2 class="color-dark-blue fz17 fw700 mar-t-0 mar-b-15 mar-b-20">{{ config('settings')['translate']['label_header'] }}</h2>
                    <div class="row mar-b-20">
                        <div class="col-lg-3 col-sm-12">
                            <label class="pad-t-7 fw500">{{ config('settings')['translate']['label_title'] }}</label>
                        </div>
                        <div class="col-lg-9">
                            <input type="text" name="translate[title]" placeholder="{{ config('settings')['translate']['title'] }}" class="form-control" value="{{ !empty($settings['translate']['title']) ? $settings['translate']['title'] : '' }}">
                        </div>
                    </div>
                    <div class="row mar-b-20">
                        <div class="col-lg-3 col-sm-12">
                            <label class="pad-t-7 fw500">{!! __('settings.lab_description_no_review') !!}</label>
                        </div>
                        <div class="col-lg-9">
                            <input class="form-control" type="text" name="translate[text_empty_review]"
                                           placeholder="{{ config('settings')['translate']['text_empty_review'] }}"
                                           value="{{ !empty($settings['translate']['text_empty_review']) ? $settings['translate']['text_empty_review'] : '' }}">
                        </div>
                    </div>
                    <div class="row mar-b-20">
                        <div class="col-lg-3 col-sm-12">
                            <label class="pad-t-7 fw500">{!! __('settings.label_description') !!}</label>
                        </div>
                        <div class="col-lg-9">
                            <input type="text" name="translate[text_total]"
                                   placeholder="{{ config('settings')['translate']['text_total'] }}"
                                   value="{{ !empty($settings['translate']['text_total']) ? $settings['translate']['text_total'] : '' }}" class="form-control">
                        </div>
                    </div>


                    <div class="row mar-b-20">
                        <div class="col-lg-3 col-sm-12">
                            <label class="pad-t-7 fw500">{!! __('settings.label_description_plural') !!}</label>
                        </div>
                        <div class="col-lg-9">
                            <input type="text" name="translate[text_total_multi]"
                                   placeholder="{{ config('settings')['translate']['text_total_multi'] }}"
                                   value="{{ !empty($settings['translate']['text_total_multi']) ? $settings['translate']['text_total_multi'] : '' }}" class="form-control">
                        </div>
                    </div>

                    <div class="row mar-b-20">
                        <div class="col-lg-3 col-sm-12">
                            <label class="pad-t-7 fw500"> {{ config('settings')['translate']['label_submit_button'] }} </label>
                        </div>
                        <div class="col-lg-9">
                            <input type="text" name="translate[button_add]"  placeholder="{{ config('settings')['translate']['button_add'] }}"
                                   value="{{ !empty($settings['translate']['button_add']) ? $settings['translate']['button_add'] :'' }}" class="form-control">
                        </div>
                    </div>

                    <div class="row mar-b-20">
                        <div class="col-lg-3 col-sm-12">
                            <label class="pad-t-7 fw500"> {{ config('settings')['translate']['message_thanks'] }} </label>
                        </div>
                        <div class="col-lg-9">
                                <input 
                                    class="input-base-required form-control" type="text" name="translate[message_thanks_has_approve]"
                                    placeholder="{{ config('settings')['translate']['message_thanks_has_approve'] }}"
                                    value="{{ !empty($settings['translate']['message_thanks_has_approve']) ? $settings['translate']['message_thanks_has_approve'] : '' }}">
                        </div>
                    </div>

                    <h2 class="color-dark-blue fz17 fw700 mar-t-0 mar-b-15 mar-b-20">{{ config('settings')['translate']['label_name_edit'] }}</h2>
                    <div class="row mar-b-20">
                        <div class="col-lg-3 col-sm-12">
                            <label class="pad-t-7 fw500">{{ config('settings')['translate']['label_name'] }}</label>
                        </div>
                        <div class="col-lg-9">
                            <input type="text" name="translate[label_name]" placeholder="{{ config('settings')['translate']['label_name'] }}"
                                   value="{{ !empty($settings['translate']['label_name']) ? $settings['translate']['label_name'] : '' }}" class="form-control">
                        </div>
                    </div>
                    <div class="row mar-b-20">
                        <div class="col-lg-3 col-sm-12">
                            <label class="pad-t-7 fw500">{{ config('settings')['translate']['label_notice'] }}</label>
                        </div>
                        <div class="col-lg-9">
                            <input type="text" name="translate[error_required]" placeholder="{{ config('settings')['translate']['error_required'] }}"
                                   value="{{ !empty($settings['translate']['error_required']) ? $settings['translate']['error_required'] : '' }}" class="form-control">
                        </div>
                    </div>

                    <h2 class="color-dark-blue fz17 fw700 mar-t-0 mar-b-15 mar-b-20">{{ config('settings')['translate']['label_email_edit'] }}</h2>
                    <div class="row mar-b-20">
                        <div class="col-lg-3 col-sm-12">
                            <label class="pad-t-7 fw500">{{ config('settings')['translate']['label_email'] }}</label>
                        </div>
                        <div class="col-lg-9">
                            <input type="text" name="translate[label_email]" placeholder="{{ config('settings')['translate']['label_email'].' ( '.config('settings')['translate']['optional'].' )'}}"
                                   value="{{ !empty($settings['translate']['label_email']) ? $settings['translate']['label_email'] : ''}}" class="form-control">
                        </div>
                    </div>
                    <div class="row mar-b-20">
                        <div class="col-lg-3 col-sm-12">
                            <label class="pad-t-7 fw500">Notice</label>
                        </div>
                        <div class="col-lg-9">
                            <input type="text" name="translate[error_email]"
                                   value="{{ !empty($settings['translate']['error_email']) ? $settings['translate']['error_email'] : '' }}"
                                   placeholder="{{ config('settings')['translate']['error_email'] }}"
                                   class="form-control">
                        </div>
                    </div>

                    <h2 class="color-dark-blue fz17 fw700 mar-t-0 mar-b-15 mar-b-20"> {{ config('settings')['translate']['label_your_rating'] }}</h2>
                    <div class="row mar-b-20">
                        <div class="col-lg-3 col-sm-12">
                            <label class="pad-t-7 fw500">{{ config('settings')['translate']['label_your_rating'] }}</label>
                        </div>
                        <div class="col-lg-9">
                            <input type="text"  name="translate[label_your_rating]" value="{{ !empty($settings['translate']['label_your_rating']) ? $settings['translate']['label_your_rating']  :'' }}"
                                   placeholder="{{ config('settings')['translate']['label_your_rating'] }}" class="form-control">
                        </div>
                    </div>
                    <div class="row mar-b-20">
                        <div class="col-lg-3 col-sm-12">
                            <label class="pad-t-7 fw500">{{ config('settings')['translate']['label_placeholder'] }} </label>
                        </div>
                        <div class="col-lg-9">
                            <input type="text" name="translate[text_write_comment]"
                                   value="{{ !empty($settings['translate']['text_write_comment']) ? $settings['translate']['text_write_comment'] : '' }}"
                                   placeholder="{{ config('settings')['translate']['text_write_comment'] }}" class="form-control">
                        </div>
                    </div>
                    <div class="row mar-b-20">
                        <div class="col-lg-3 col-sm-12">
                            <label class="pad-t-7 fw500">{{ config('settings')['translate']['label_notice'] }}</label>
                        </div>
                        <div class="col-lg-9">
                            <input type="text" name="translate[error_rating_required]" placeholder="{{ config('settings')['translate']['error_required'] }}"
                                   value="{{ !empty($settings['translate']['error_rating_required']) ? $settings['translate']['error_rating_required'] : config('settings')['translate']['error_required'] }}" class="form-control">
                        </div>
                    </div>

                    <h2 class="color-dark-blue fz17 fw700 mar-t-0 mar-b-15 mar-b-20">{{ config('settings')['translate']['label_footer'] }}</h2>
                    <div class="row mar-b-20">
                        <div class="col-lg-3 col-sm-12">
                            <label class="pad-t-7 fw500"> {{ config('settings')['translate']['label_image'] }} </label>
                        </div>
                        <div class="col-lg-9">
                            <input type="text" name="translate[label_image]" value="{{ !empty($settings['translate']['label_image']) ? $settings['translate']['label_image'] : '' }}"
                                   placeholder="{{ config('settings')['translate']['label_image'] }}" class="form-control">
                        </div>
                    </div>

                    <div class="row mar-b-20">
                        <div class="col-lg-3 col-sm-12">
                            <label class="pad-t-7 fw500">{{ config('settings')['translate']['label_send_button'] }}</label>
                        </div>
                        <div class="col-lg-9">
                            <input type="text" name="translate[button_submit]" value="{{ !empty($settings['translate']['button_submit']) ? $settings['translate']['button_submit'] : '' }}"
                                   placeholder="{{ config('settings')['translate']['button_submit'] }}" class="form-control">
                        </div>
                    </div>
                </div>
                <!-- END: col-left -->
                <div class="col-lg-6">
                    <div class="wrapper-preview-real bg-dark-blue">
                        <div class="preview-real__header mar-b-15" style="display: flex; align-items: center;">
                            <label class="fz18"> {{ config('settings')['translate']['label_live_review'] }} </label>
                            <div class="clearfix"></div>
                        </div>

                        <div class="preview-real__content bg-white">
                            <h3 class="fw-bold fz20 color-dark-blue mar-t-0 mar-b-25" id="realHeaderTitle" >{{ !empty($settings['translate']['title']) ? $settings['translate']['title'] : '' }}</h3>
                            <!-- Media Object Point -->
                            <div class="media media--point mar-b-20 o">
                                <div class="media-left media-top">
                                    <button type="button" class="button button--primary fw600 fz25 pad-0">4.5</button>
                                </div>

                                <div class="media-body media-body--write-new">
                                    <div class="media-body_rating">
                                        <div class="media_rating-box d-inline-block text-center">
                                            <input type="hidden" class="alr-rating" data-filled="alr-icon-star" data-empty="alr-icon-star" data-fractions="1" data-readonly value="4.5"/>
                                        </div>
                                    </div>
                                    <p class="mr0" id="realHeaderDescript">{{ !empty($settings['translate']['text_total_multi']) ? $settings['translate']['text_total_multi'] : '' }}</p>

                                    <button type="button" class="button button--primary fw600 pad-0" id="realHeaderButtonWrite" style="overflow: hidden;">{{ !empty($settings['translate']['button_add']) ? $settings['translate']['button_add'] :'' }}</button>
                                </div>
                            </div>
                            <!-- END: Media Object Point -->

                            <button type="button" class="button button--primary w-100 mar-b-25 butn-thank fz13 fw500" id="realButtonThank" style="white-space: inherit; line-height: 1.3;">
                                {{ !empty($settings['translate']['message_thanks_has_approve']) ? $settings['translate']['message_thanks_has_approve'] : '' }}
                            </button>

                            <div class="preview-real__wrapper-media">
                                <div class="form-group">
                                    <label class="mar-b-15" id="realName">{{ !empty($settings['translate']['label_name']) ? $settings['translate']['label_name'] : '' }}</label>
                                    <input type="text" class="form-control mar-b-10">
                                    <p class="color-pink fz11" id="realNameNotice">{{ !empty($settings['translate']['error_required']) ? $settings['translate']['error_required'] : '' }}</p>
                                </div>

                                <div class="form-group">
                                    <label class="mar-b-15" id="realEmail">{{ !empty($settings['translate']['label_name']) ? $settings['translate']['label_email'] : '' }}</label>
                                    <input type="email" class="form-control mar-b-10">
                                    <p class="color-pink fz11" id="realEmailNotice">{{ !empty($settings['translate']['error_email']) ? $settings['translate']['error_email'] : '' }}</p>
                                </div>

                                <div class="form-group">
                                    <label class="mar-b-15"  id="realRating">{{ !empty($settings['translate']['label_name']) ? $settings['translate']['label_your_rating'] : '' }} :</label>
                                    <span style="vertical-align: sub;">
                                        <input type="hidden" class="alr-rating" data-filled="alr-icon-star" data-empty="alr-icon-star" data-fractions="1" value="5" data-readonly />
                                    </span>
                                </div>

                                <div class="form-group mar-b-25">
                                    <textarea class="form-control mar-b-10" rows="5" placeholder="{{ !empty($settings['translate']['text_write_comment']) ? $settings['translate']['text_write_comment'] :config('settings')['translate']['text_write_comment'] }}" id="realRatingPlaceholder"></textarea>
                                    <p class="color-pink fz11" id="realRatingPlaceholderNotice">{{ !empty($settings['translate']['error_rating_required']) ? $settings['translate']['error_rating_required'] : config('settings')['translate']['error_required'] }}</p>
                                </div>

                                <div class="mar-b-15">
                                    <button type="button" class="button button--default fz11 color-dark-blue" id="realFooterAddImg">{{ !empty($settings['translate']['button_submit']) ? $settings['translate']['label_image'] : '' }}</button>
                                </div>

                                <div class="text-right">
                                    <button type="button" class="button button--primary fz11" id="realFooterSend"> {{ !empty($settings['translate']['button_submit']) ? $settings['translate']['button_submit'] : '' }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: col-right -->
            </div>
            <!-- END: row -->
            <div class="row">
                <div class="col-md-12 text-align-lg-c">
                    <button type="submit" class="button button--primary mar-r-15">Save Settings</button>
                    <button type="reset"  class="button button--default resetLocalTranslate" >Reset To Default</button>
                </div>
            </div>
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
        </div>
        {!! csrf_field() !!}
    </form>
    @include('settings/modal-confirm-reset-local-translate')
    @include('settings/notify_save_success')
@endsection

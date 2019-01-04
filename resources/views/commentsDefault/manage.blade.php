@extends('layout.backend', ['page_title' => 'Default reviews'])

@section('header')
    @include('layout.header', ['page_title' => 'Default reviews'])
@endsection

@section('container_content')
    @include('sections.alert_error')
    @include('sections.alert_success')

    <script>
        var limitCommentDefault = "{{ $shopPlanInfo['sample_reviews'] }}";
    </script>
    {{--<button onclick="chrome.webstore.install()" id="install-button" style="display: none">Add to Chrome</button>--}}

    <div class="default-review-wrap {{ !empty($shopPlanInfo['sample_reviews']) ? 'active' : '' }}">
        <div class="default-review-container">
            <h2 class="default-review-title" style="position: relative">Default reviews
                @if(empty($shopPlanInfo['sample_reviews']))
                    <div class="tooltip fade right in" role="tooltip">
                        <div class="tooltip-arrow"></div>
                        <div class="tooltip-inner">
                            <span>Only available in<br> Premium version</span>
                            <a class="ars-btn" href="{{ route('apps.upgrade') }}">Upgrade to Premium
                                version</a>
                        </div>
                    </div>
                @endIF
            </h2>
            <p>Add default reviews to your no-review products. You can manually or randomly choose which reviews to be shown.</p>
            {{--<div class="switch-default-review-wrap">
                <label class="ars-switch" for="switch-default-review">
                    <input id="{{ !empty($shopPlanInfo['sample_reviews']) ? 'switch-default-review' : '' }}" type="checkbox" {{ !empty($settings['is_comment_default']) ? "checked" :""  }}>
                    <span class="ars-switch-btn">
                            <span>On</span>
                            <span>Off</span>
                        </span>
                </label>
            </div>--}}
        </div>
        <div class="default-review-list-wrap">
            <h3>1.1 Manage preloaded reviews</h3>
            <div class="default-review-list">
                <div class="ars-table">
                    <div class="ars-table-head">
                        <div class="ars-table-col col-default-name">{{ __('reviews.label_name') }}</div>
                        <div class="ars-table-col col-default-review">{{ __('reviews.label_reviews') }}</div>
                        <div class="ars-table-col col-default-feedback">{{ __('reviews.label_feedback') }}</div>
                        <div class="ars-table-col col-default-country">{{ __('reviews.label_country') }}</div>
                        <div class="ars-table-col col-default-action">{{ __('reviews.label_action') }}</div>
                    </div>
                    <div class="ars-table-body">
                        @if(!empty($list->total()))
                            @foreach($list as $v)
                                <div class="ars-table-row ars-table-row-cmd-{{ $v->id }}">
                                    <div class="ars-table-col col-default-name">
                                        <p>{{ $v->author }}</p>
                                    </div>
                                    <div class="ars-table-col col-default-review">
                                        {!! \App\Helpers\Helpers::getStarRating($v->star) !!}
                                    </div>
                                    <div class="ars-table-col col-default-feedback">
                                        {{ $v->content }}
                                    </div>
                                    <div class="ars-table-col col-default-feedback">
                                        <img src="{{ url('/images/flag/'.strtolower($v->country).'.png') }}"
                                             alt=""> {{ $v->country }}
                                    </div>
                                    <div class="ars-table-col col-default-action">
                                        <a class="ars-btn  edit-default-review-btn" href="javascript:void(0)"
                                           data-id="{{ $v->id }}">EDIT</a>
                                        <a class="ars-btn-o delete-comment-default" href="javascript:void(0)"
                                           data-id="{{ $v->id }}">REMOVE</a>
                                    </div>
                                </div>
                            @endForeach
                        @endIf

                    </div>
                </div>
                <div class="add-default-review-wrap">
                    <a class="add-default-review-btn">+ Add more reviews</a>
                </div>
                @if($list->total())
                    <div class="pagination-wrap">
                        {{ $list->appends($filters)->links('vendor.pagination.alireview') }}
                    </div>
                @endIf
            </div>
        </div>
        <div class="random-default-wrap">
            {{--<form id="form-save-random-review">
                <h3>1.2 Randomly show default reviews</h3>
                <p>Number of random default reviews per product:</p>
                <div class="ars-field">
                    <label>
                        <span>From</span>
                        <input type="text" name="rand_comment_default[from]" value="{{ !empty($settings['rand_comment_default']['from']) ? $settings['rand_comment_default']['from'] : 0 }}">
                        <span>to</span>
                        <input type="text" name="rand_comment_default[to]" value="{{ !empty($settings['rand_comment_default']['to']) ? $settings['rand_comment_default']['to'] : 0 }}">
                    </label>
                </div>

                <div>
                    <button class="ars-submit-btn" type="submit">SAVE SETTINGS</button>
                </div>
            </form>--}}
            <h3>1.2 Import to products</h3>
            <p>Import  default reviews random to all products when product empty reviews</p>
            <a class="ars-submit-btn importDefaultReviews" style="margin-top: 0;" href="javascript:void(0)">Import</a>
            <button class="ars-btn-o delete-imported-reviews" id="deleteDefaultReviews" style="margin-top: 0; margin-left: 2rem; padding: 0 25px; height: 60px;">Delete imported reviews</a>
        </div>
    </div>

    @include('commentsDefault.modal-save-commentDefault')
    @include('commentsDefault.modal-delete-commentDefault')
    @include('commentsDefault.modal-import-commentDefault')
    @include('commentsDefault.modal-delete-commentImported')
@endsection
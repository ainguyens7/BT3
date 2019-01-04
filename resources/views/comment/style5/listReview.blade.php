<div class="alireview-app alireview-app--light alireview-rating-point-{{ $rating_point }} alireview-rating-card-{{ $rating_card }}">
    <div style="{{ empty($product_id)  ? "display : none" : ""}}">
        <div class="alireview-container">
            <div class="alireview-app-header">
                <div class="alireview-head">
                    <div class="alireview-display">
                        {{ $translate['title'] }}
                    </div>
                </div>
                <div class="alireview-quick-view">
                    <div class="alireview-summary-review">
                        <div class="alireview-number-total-review">
                            @if(!empty($avg_star))
                                <span>{{ (strpos($avg_star,'.') !== false ? $avg_star : $avg_star.'.0')  }}</span>
                            @else
                                <span class="alr-icon-star"></span>
                            @endIf
                        </div>
                        <div class="alireview-summary-rating">
                            <div class="alireview-rating">
                                <input type="hidden" class="alr-rating" data-filled="alr-icon-star" data-readonly data-empty="alr-icon-star" data-fractions="3" value="{{ isset($avg_star) ? $avg_star : 0 }}"/>
                            </div>
                            <div class="alireview-summary-detail">
                                <div class="alireview-summary-content">
                                    @php
                                        $trsl_text_empty_review = !empty($translate['text_empty_review']) ? $translate['text_empty_review'] : config('settings')['translate']['text_empty_review'];
                                        $trsl_text_total = !empty($translate['text_total']) ? $translate['text_total'] : config('settings')['translate']['text_total'];
                                        $trsl_text_total_multi = !empty($translate['text_total_multi']) ? $translate['text_total_multi'] : config('settings')['translate']['text_total_multi'];
                                    @endphp
                                    @if(empty($total_review))
                                        {{ $trsl_text_empty_review }}
                                    @else
                                        @if($total_review == 1)
                                            {{ str_replace('{reviews}',$total_review,$trsl_text_total) }}
                                        @else
                                            {{ str_replace('{reviews}',number_format($total_review),$trsl_text_total_multi) }}
                                        @endIF
                                    @endIf
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="alireview-write-review alireview-btn-add-review">
                        <button type="button" class="alireview-button alireview-button-default alireview-button-md"
                                onClick="jQuery('.reviewFormHead').toggle('slow')">{{ $translate['button_add'] }}</button>
                        @if(!empty($shopPlanInfo['code_power']))
                            <span class="alireview-power-by" style=" display: block !important; visibility: visible !important;">
                        <img src="{{ cdn('images/power-by-alireviews.png') }}" alt="" style=" display: inline-block !important; visibility: visible !important;">
                        Powered by
                        <a href="https://apps.shopify.com/ali-reviews?utm_source=referral&utm_medium=free_version" target="_blank" style=" display: inline-block !important; visibility: visible !important;" title="Ali Reviews">
                            Ali Reviews
                        </a>
                    </span>
                        @endIf
                    </div>
                </div>
            </div>

        </div>
        <div class="reviewFormHead">
            @include('comment.style3.review')
        </div>
    </div>
    <div class="alireview-app-body">
        <div class="alireview-container">
            <div class="alireview-list-reviews" id="alireview-list-reviews">

                @if(! empty($comments))
                    @foreach($comments as $comment)
                        <div class="alireview-review-item-wrap">
                            <div class="alireview-review-item">
                                <div class="alireview-review-item-container">
                                    <div class="alireview-review-item-head">
                                        @if(is_array($comment->img) && in_array('image', $setting['section_show']))
                                            <div class="alireview-review-image">
                                                <div class="alireview-image-container">
                                                    <div class="alireview-image-feature">
                                                        @foreach($comment->img as $k=>$v)
                                                            <div class="alireview-image-slide" data-jslghtbx="{{ $v }}"
                                                                 data-jslghtbx-group="id{{ $comment->id }}">
                                                                <img class="alireview-image-rounded" src="{{ $v }}"
                                                                     alt="">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    @if(is_array($comment->img) && count($comment->img) > 1 )
                                                        <span class="alireview-image-hidden-more">
                                                            <span>
                                                                {{ count($comment->img) }}
                                                            </span>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                        <div class="alireview-review-item-user-info">
                                            <div class="alireview-review-user-info-head">
                                                <div class="alireview-review-user-info-left">
                                                    <span class="alireview-review-user-info-avatar-wrap" style="{{ (in_array('avatar', $setting['section_show'])) ? '' : 'background: none; height: 40px; overflow:hidden;' }}">
                                                        @if(in_array('avatar', $setting['section_show']))
                                                            <span class="alireview-review-user-info-avatar"
                                                                  style="background-image: cdn({{ isset($comment->avatar) ? cdn($comment->avatar) : cdn('images/avatar/abstract/avatar26.jpg') }})"></span>
                                                        @else
                                                            <span class="alireview-review-user-info-avatar"
                                                                  style="background: none"></span>
                                                        @endIF
                                                    </span>

                                                    <div class="alireview-review-user-info-name">
                                                        <div class="alireview-user-info-title">
                                                            @if(!in_array('hide_name',$setting['section_show']))
                                                                {{ $comment->author }}
                                                            @else
                                                                {{ \App\Helpers\Helpers::shortName($comment->author) }}
                                                            @endIf
                                                            <img class="alireview-verified-image"
                                                                 src="{{ cdn('images/verified.png') }}"/>
                                                        </div>
                                                        @if(in_array('country', $setting['section_show']))
                                                            <div class="alireview-flag-image">
                                                                <span class="ali-flag-slc {{ strtolower($comment->country) }}"></span>
                                <span class="fz13 color-grey-800">{{ $comment->country }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="alireview-rating">
                                                <input type="hidden" class="alr-rating"   data-readonly data-filled="alr-icon-star" data-empty="alr-icon-star" data-fractions="3" value="{{ $comment->star }}"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="alireview-review-item-body">
                                        <div class="alireview-review-content">
                                            <div class="alireview-content-description">
                                                @if($comment->content != '')
                                                    {{ str_replace("\'","'",$comment->content) }}
                                                @endif
                                            </div>
                                        </div>
                                        <a href="javascript:void (0)" class="alireview-comment-like {{ !empty($comment->likeClass) ? $comment->likeClass : '' }}"
                                           data-comment_id="{{ $comment->id }}"
                                           data-shop_id="{{ $shop_id }}">
                                           <span class="alireview-icon-like"></span>
                                            <span class="alireview-number-like alireview-number-like-{{ $comment->id }}">
                                                @if(!empty($comment->like))
                                                    {{ $comment->like }}
                                                @endIf
                                            </span>
                                            
                                        </a>
                                        <a href="javascript:void (0)" class="alireview-comment-unlike {{ !empty($comment->UnlikeClass) ? $comment->UnlikeClass : '' }}"
                                           data-comment_id="{{ $comment->id }}"
                                           data-shop_id="{{ $shop_id }}">
                                           <span class="alireview-icon-like"></span>
                                            <span class="alireview-number-unlike alireview-number-unlike-{{ $comment->id }}">
                                                @if(!empty($comment->unlike))
                                                    {{ $comment->unlike }}
                                                @endIf
                                            </span>
                                        </a>
                                        <div class="alireview-review-user-info-right">
                                            @if(in_array('date_time', $setting['section_show']))
                                                <span class="alireview-review-date">
                                              {{ \App\Helpers\Helpers::human_time_diff(strtotime($comment->created_at),'',$translate) }}
                                            </span>
                                            @endIf
                                        </div>
                                        @if(empty($product_id) && !empty($comment->product_info))
                                            <a href="{{ $comment->product_info->product_link }}" class="alireview-product-link" target="_blank">{{ $comment->product_info->title }}</a>
                                        @endIf
                                    </div>
                                </div>
                            </div>
                        </div>

                    @endforeach
                @endif
            </div>
        </div>
    </div>
    {{ $comments->links('vendor.pagination.frontend') }}
</div>
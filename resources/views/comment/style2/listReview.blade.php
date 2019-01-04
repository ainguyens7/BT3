@php
    $translate_err = [
        'error_name_required' => !empty($translate['error_required']) ? $translate['error_required'] : config('settings')['translate']['error_required'],
        'error_email' => !empty($translate['error_email']) ? $translate['error_email'] : config('settings')['translate']['error_email'],
        'error_rating_required' => !empty($translate['error_rating_required']) ? $translate['error_rating_required'] : config('settings')['translate']['error_required']
    ];
@endphp

<div class="customer-alireview alireview-rating-point-{{ $rating_point }} alireview-rating-card-{{ $rating_card }}">
    <input type="hidden" name="summary-star" value="all">
    <input type="hidden" name="sort-type" value="all">
    <input type="hidden" name="translate" value="{{ json_encode($translate_err) }}">

    <div style="{{ empty($product_id)  ? "display : none" : ""}}">
        <div class="alireview-title">
            <div class="alireview-form-title">{{ $translate['title'] }}</div>
        </div>
        <div class="alireview-powered"></div>
        <div class="alireview-header-summary">
            <div class="alireview-summary">
                <div class="alireview-number-total-review">
                    @if(!empty($avg_star))
                        <span>{{ (strpos($avg_star,'.') !== false ? $avg_star : $avg_star.'.0')  }}</span>
                    @else
                        <span class="alr-icon-star"></span>
                    @endIf
                </div>
                <div class="alireview-total-review">
                    <input type="hidden" class="alr-rating" data-filled="alr-icon-star" data-empty="alr-icon-star" data-fractions="1" value="{{ isset($avg_star) ? $avg_star : 0 }}" data-readonly/>
                    <div class="alireview-total-text">
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
                                {{ str_replace('{reviews}',$total_review,$trsl_text_total_multi) }}
                            @endIF
                        @endIf
                    </div>
                </div>
            </div>

            <div class="alireview-btn-add-review">
                <button type="button" class="btn-add-alireview"
                        onClick="jQuery('.reviewFormHead').toggle('slow')">{{ $translate['button_add'] }}
                </button>
                @if(!empty($shopPlanInfo['code_power']))
                    <span class="alireview-power-by" style="visibility: visible !important;">
                        <img src="{{ cdn('images/alireview-icon-extension.png') }}" width="16px">
                        Powered by 
                        <a href="https://apps.shopify.com/ali-reviews?utm_source=referral&utm_medium=free_version" target="_blank" style=" display: inline-block !important; visibility: visible !important;" title="Ali Reviews">
                            Ali Reviews
                        </a>
                    </span>
                @endIf
            </div>
            @if(!empty($setting['display_rate_list']) && $total_review > 0)
                @include('comment.summary-rating')
            @endif
        </div>
        <div class="reviewFormHead">
            @include('comment.style2.review')
        </div>
    </div>
    {{--  End form review --}}
    
    
    @if($comments->total())
        @if (!empty($product_id))
            @if(isset($setting['display_advance_sort']) && $setting['display_advance_sort'] !== 0)
                <div class="alireview-sort">
                    <div class="alireview-sort__wrap">
                        <div class="alireview-sort__label">
                    <span class="icon-filter">
                        <img src="{{ cdn('images/icons/icon-filter.png') }}">
                    </span>
                        </div>

                        <ul class="alireview-sort__type"></ul>
                    </div>
                </div>
            @endif
        @endif
        <div class="alireview-result">
            <div class="list-alireview">
                @foreach($comments as $comment)
                    <div class="alireview-row  @if(is_array($comment->img) && in_array('image', $setting['section_show'])) has-prod-imgs @endif">
                        <div class="alireview-row-wrap">
                            <div class="alireview-thumbnail">
                                @if(is_array($comment->img) && in_array('image', $setting['section_show']))
                                    @if( count($comment->img) > 1 )
                                        <div class="count-img" count-img="{{ count($comment->img) }}">{{ count($comment->img)  }}</div>
                                    @endif

                                    <div class="alireview-product-img alr-grid">
                                        @foreach($comment->img as $k=>$v)
                                            @if($comment->source == "web")
                                                @php
                                                    $thumb = str_replace($shop_id,$shop_id.'/_thumb',$v)
                                                @endphp
                                            @endIf
                                            <img class="jslghtbx-thmb" src="{{ $comment->source == "web" ? $thumb : $v }}" data-jslghtbx="{{ $v }}"
                                                data-jslghtbx-group="group{{$comment->id}}">
                                        @endforeach
                                    </div>
                                @endif

                                @if(in_array('avatar', $setting['section_show']))
                                    <img class="alireview-avatar"
                                        src="{{ isset($comment->avatar) ? cdn($comment->avatar) : cdn('images/avatar/abstract/avatar26.jpg') }}"/>
                                @endif

                                <div class="alireview-author">
                                    <span>
                                        @if(in_array('hide_name',$setting['section_show']))
                                            {{ $comment->author }}
                                        @else
                                            {{ \App\Helpers\Helpers::shortName($comment->author) }}
                                        @endIf
                                    </span>

                                    <div class="alireview-verified alr-icon-verified"></div>
                                </div>
                                <div class="alireview-national-info">
                                    @if(in_array('country', $setting['section_show']))
                                        <span class="ali-flag-slc {{ strtolower($comment->country) }}"></span>
                                        <span class="ali-flag-text">{{ $comment->country }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="alireview-desc">
                                <div class="alireview-desc-content">
                                    <div class="alireview-header clearfix">
                                        <div class="alireview-status">
                                            <input type="hidden" class="alr-rating" data-filled="alr-icon-star" data-empty="alr-icon-star" data-fractions="3" data-readonly value="{{ $comment->star }}"/>
                                            <div class="alireview-verified alr-icon-verified"></div>
                                            @if(isset($setting['active_date_format']) && $setting['active_date_format'] !== 0)
                                                <time class="alireview-date"> {{ date($setting['date_format'], strtotime($comment->created_at)) }}</time>
                                            @endif
                                        </div>
                                    </div>
                                    @if(is_array($comment->user_order_info) && in_array('user_order_info', $setting['section_show']))
                                        <div class="alireview-info-product">
                                            @foreach($comment->user_order_info as $k=>$v)
                                                <div class="alireview-item"><label>{{ $v->key }}</label> : {{ $v->value }}</div>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if($comment->content != '')
                                        <div class="alireview-post">
                                            <p>
                                                {{ str_replace("\'","'",$comment->content) }}
                                            </p>
                                        </div>
                                    @endif

                                    @if(is_array($comment->img) && in_array('image', $setting['section_show']))
                                        <div class="alireview-product-img">
                                            @foreach($comment->img as $k=>$v)
                                                @if($comment->source == "web")
                                                    @php
                                                        $thumb = str_replace($shop_id,$shop_id.'/_thumb',$v)
                                                    @endphp
                                                @endIf
                                                <img class="jslghtbx-thmb" src="{{ $comment->source == "web" ? $thumb : $v }}" data-jslghtbx="{{ $v }}"
                                                    data-jslghtbx-group="group{{$comment->id}}">
                                            @endforeach
                                        </div>
                                    @endif
                                    
                                    <div class="alireview-like-wrap">
                                        <a href="javascript:void (0)" class="alireview-comment-like {{ !empty($comment->like) && $comment->like > 0 ?  'active' : '' }}"
                                        data-comment_id="{{ $comment->id }}"
                                        data-shop_id="{{ $shop_id }}">
                                            <span class="alireview-number-like alireview-number-like-{{ $comment->id }}">
                                                @if(!empty($comment->like))
                                                    {{ $comment->like }}
                                                @endIf
                                            </span>
                                            <span class="alireview-icon-like"></span>
                                        </a>
                                        <a href="javascript:void (0)" class="alireview-comment-unlike {{ !empty($comment->unlike) && $comment->unlike > 0 ? 'active' : '' }}"
                                        data-comment_id="{{ $comment->id }}"
                                        data-shop_id="{{ $shop_id }}">
                                            <span class="alireview-number-unlike alireview-number-unlike-{{ $comment->id }}">
                                                @if(!empty($comment->unlike))
                                                    {{ $comment->unlike }}
                                                @endIf
                                            </span>
                                            <span class="alireview-icon-like"></span>
                                        </a>

                                        @if(isset($setting['active_date_format']) && $setting['active_date_format'] !== 0)
                                            <time class="alireview-date"> {{ date($setting['date_format'], strtotime($comment->created_at)) }}</time>
                                        @endif
                                    </div>
                                    @if(empty($product_id) && !empty($comment->product_info))
                                        <a href="{{ $comment->product_info->product_link }}" class="alireview-product-link" target="_blank">{{ $comment->product_info->title }}</a>
                                    @endIf
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $comments->links('vendor.pagination.frontend') }}
        </div>
    @endif
</div>


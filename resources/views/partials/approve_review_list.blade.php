@if($listReview->total())
    <div class="table-responsive">
        <table class="table table-hover table-ali-custom table-mid" id="table-select-all">
            <thead>
                <tr>
                    <th width="5%" class="text-center">#</th>
                    <th width="19.79%">{{ __('reviews.label_name') }}</th>
                    <th width="7.916%">{{ __('reviews.label_rating') }}</th>
                    <th width="23.748%">{{ __('reviews.label_feedback') }}</th>
                    <th width="3.958%">{{ __('reviews.label_photo') }}</th>
                    <th width="23.748%">{{ __('reviews.label_products') }}</th>
                    <th width="15.832%">{{ __('reviews.label_action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($listReview as $k => $review)
                <tr class="row-selected">
                    <td class="text-center">
                        <label class="wrap-custom-box">
                            <input type="checkbox" class="row-select"
                            id="review-{{ $review->id }}" 
                            name="listReviewCheck[]" value="{{ $review->id }}"
                            data-source="{{$review->source}}"
                            data-product-id="{{$review->product_id}}"
                            >
                            <span class="checkmark-ckb"></span>
                        </label>
                    </td>
                    <td>
                        <div class="media">
                            <div class="media-left media-middle">
                                <img src="{{ asset($review->avatar) }}" class="media-object img-rounded img-circle" style="width: 50px" alt="">
                            </div>

                            <div class="media-body">
                                <h4 class="media-heading fz13 fw600 p-realative">
                                    <a href="#">{{ $review->author }}</a>
                                </h4>

                                <div class="media-left_location p-relative">
                                    <span class="ali-flag-slc {{ strtolower($review->country) }}"></span>
                                    <span class="fz12 color-grey-800">{{ $review->country }}</span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="td-rating">
                        @php
                            $avg_rating = \App\Helpers\Helpers::getStarRating($review->star);
                        @endphp
                        <input type="hidden" class="rating-tooltip-manual" data-filled="ali-rating ali-rating--star icon-ic-star-24px" data-empty="ali-rating ali-rating--star icon-ic-star-border-24px"
                            data-fractions="{{ $avg_rating }}" />
                    </td>
                    <td>
                        <p class="fz13 text-over-2 mr0 table-note">{{ $review->content }}</p>
                    </td>
                    <td>
                        @if (!empty($review->img) && is_array($review->img))
                            @if (count(array_slice($review->img, 2)) > 0)
                                <div class="dropdown d-inline-block">
                                    <button class="button dropdown-toggle button--primary more-photos" type="button" id="dropdownMenu1" data-toggle="dropdown">
                                        <span>+{{ count(array_slice($review->img, 2)) }}</span>
                                    </button>

                                    <div class="dropdown-menu dropdown-menu--rdb dropdown-menu--top dropdown-menu--right" aria-labelledby="dropdownMenu1">
                                        <div class="drop-rdb_1">
                                            @foreach(array_slice($review->img, 2) as $img)
                                                <label>
                                                    <input type="radio" name="rdb">
                                                    <img src="{{ $review->source == "web" ? str_replace(session('shopId'),session('shopId').'/_thumb',$img)  : $img }}" alt="" width="34px" height="34px" style="object-fit: cover">
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('reviews.product',['productId' => !empty($review->product_info->id) ? $review->product_info->id : 0]) }}" target="_blank" class="fz13 text-over-2 mr0 table-note">
                                        {{ !empty($review->product_info->title) ? $review->product_info->title : '' }}</a>
                    </td>
                    <td>
                        <button class="button button--primary  fz12" data-type="approve"
                                           data-comment_id="{{ $review->id }}">{{ __('reviews.hide_review') }}</button>
                        <button class="button button--default  btn--icon" data-toggle="tooltip" data-placement="top" title="Delete" data-comment_id="{{ $review->id }}">
                            <i class="material-icons">delete</span>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    @include('partials/empty_approve_review')
@endif
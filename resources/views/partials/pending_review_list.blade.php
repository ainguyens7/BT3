@if($listReview->total())
    <div class="table-responsive-xxs">
        <table class="table table-hover table-ali-custom table-mid" id="table-select-all">
            <thead>
                <tr>
                    <th width="5%" class="text-center">#</th>
                    <th width="19.79%">{{ __('reviews.label_name') }}</th>
                    <th width="" class="hidden-xs">{{ __('reviews.label_rating') }}</th>
                    <th width="">{{ __('reviews.label_feedback') }}</th>
                    <th width="20%" class="hidden-xs">{{ __('reviews.label_photo') }}</th>
                    <th width="15%">{{ __('reviews.label_products') }}</th>
                    <th width="17%" style="min-width: 180px;">{{ __('reviews.label_action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($listReview as $k => $review)
                <tr id="ars-table-row-{{ $review->id }}">
                    <td class="text-center">
                        <label class="wrap-custom-box">
                            <input type="checkbox" class="row-select review-approve-checked"
                            id="review-{{ $review->id }}" 
                            name="listReviewCheck[]" value="{{ $review->id }}"
                            data-source="{{$review->source}}"
                            data-product-id="{{$review->product_id}}"
                            >
                            <span class="checkmark-ckb"></span>
                        </label>
                    </td>
                    <td class="break-word">
                        <div class="media">
                            <div class="media-left media-middle hidden-xs">
                                <img src="{{ asset($review->avatar) }}" class="media-object img-rounded img-circle" style="width: 50px" alt="">
                            </div>

                            <div class="media-body">
                                <h4 class="media-heading fz13 fw600 p-realative text-over-3">
                                    <a href="#">{{ $review->author }}</a>
                                </h4>

                                <div class="media-left_location p-relative">
                                    <span class="ali-flag-slc {{ strtolower($review->country) }}"></span>
                                    <span class="fz12 color-grey-800">{{ $review->country }}</span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="td-rating hidden-xs">
                        <input type="hidden" class="alr-rating" data-filled="alr-icon-star" data-empty="alr-icon-star" value="{{ $review->star }}" data-fractions="3" data-readonly/>
                    </td>
                    <td class="break-word">
                        <p class="fz13 text-over-2 mr0 table-note">{{ $review->content }}</p>
                    </td>
                    <td class="hidden-xs">
                        <div class="d-flex">
                            @if (isset($review->img) && is_array($review->img))
                                @foreach(array_slice($review->img, 0, 2) as $img)
                                    <img 
                                        data-jslghtbx-group="thmb-group-{{ $review->id }}"
                                        data-jslghtbx="{{ $review->source == "web" ? str_replace(session('shopId'),session('shopId').'/_thumb',$img)  : $img }}"
                                        src="{{ $review->source == "web" ? str_replace(session('shopId'),session('shopId').'/_thumb',$img)  : $img }}" alt="" 
                                        class="img-rounded mar-r-3 jslghtbx-thmb" width="34px" height="34px" style="object-fit: cover">
                                @endforeach
                                @if (count(array_slice($review->img, 2)) > 0)
                                    <div class="dropdown d-inline-block">
                                        <button class="button button--primary dropdown-toggle more-photos" type="button" id="dropdownMenu1" data-toggle="dropdown">
                                            <span>+{{ count(array_slice($review->img, 2)) }}</span>
                                        </button>
    
                                        <div class="dropdown-menu dropdown-menu--rdb dropdown-menu--top dropdown-menu--right" aria-labelledby="dropdownMenu1">
                                            <div class="drop-rdb_1">
                                                @foreach(array_slice($review->img, 2) as $img)
                                                    <label>
                                                        <input type="radio" name="rdb">
                                                        <img 
                                                            data-jslghtbx-group="thmb-group-{{ $review->id }}"
                                                            data-jslghtbx="{{ $review->source == "web" ? str_replace(session('shopId'),session('shopId').'/_thumb',$img)  : $img }}"
                                                            src="{{ $review->source == "web" ? str_replace(session('shopId'),session('shopId').'/_thumb',$img)  : $img }}" alt="" width="34px" height="34px" style="object-fit: cover;">
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </td>
                    <td class="break-word">
                        <a href="{{ route('reviews.product',['productId' => !empty($review->product_info->id) ? $review->product_info->id : 0]) }}" target="_blank" class="fz13 text-over-2 mr0 table-note">
                                        {{ !empty($review->product_info->title) ? $review->product_info->title : '' }}</a>
                    </td>
                    <td>
                        <button class="button button--default fz12 button--icon deleteReview pull-right" data-toggle="tooltip" data-placement="top" title="Delete" data-comment_id="{{ $review->id }}">
                            <i class="icon material-icons">delete</i>
                        </button>
                        <button class="button button--primary approveReview pull-right mar-r-5" data-type="approve" data-token="{{ csrf_token() }}"  data-comment_id="{{ $review->id }}">{{ __('reviews.title_approve') }}</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    @include('partials/empty_approve_review')
@endif
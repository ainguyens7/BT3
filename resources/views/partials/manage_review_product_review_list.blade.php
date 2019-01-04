@if($listReview->total())
    <div class="table-responsive-xxs">
        <table class="table table-hover table-ali-custom table-mid table-prod-review">
            @include('partials/product_review_thead')
            <tbody>
                @foreach($listReview as $k => $review)
                    <tr data-index="{{ $review->id }}" id="ars-table-row-{{$review->id}}">
                        <td class="text-center">
                            <label class="wrap-custom-box" for="review-{{ $review->id }}">
                                <input type="checkbox" class="row-select review-approve-checked" data-index-stt="0" id="review-{{ $review->id }}"
                                    name="listReviewCheck[]" value="{{ $review->id }}"
                                    data-source="{{$review->source}}"
                                    data-product-id="{{$product->id}}"
                                >
                                <span class="checkmark-ckb"></span>
                            </label>
                        </td>
                        <td class="text-center">
                            <label class="wrap-switch lblReviewChangeStatus">
                                <input
                                    type="checkbox"
                                    data-comment_id="{{ $review->id }}"
                                    data-product-id="{{$product->id}}"
                                    data-token="{{ csrf_token() }}"
                                    data-id="{{$review->id}}" value="1"
                                    data-source="{{$review->source}}"
                                    {{ !empty($review->status) ? 'checked' : '' }}
                                    class="switch-input reviewChangeStatus">
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </td>
                        <td class="break-word">
                            <div class="media">
                                <div class="media-body">
                                    <h4 class="media-heading fz13 fw600 p-realative">
                                        <span class="color-dark-blue text-over-3">{{ $review->author }}</span>
                                    </h4>

                                    <div class="media-left_location p-relative">
                                        <span class="ali-flag-slc {{ strtolower($review->country) }}"></span>
                                        <span class="fz13 color-grey-800">{{ $review->country }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="td-rating">
                            <div>
                                <input type="hidden" class="alr-rating" data-filled="alr-icon-star" data-empty="alr-icon-star" data-readonly data-fractions="3" value="{{ $review->star }}"/>
                            </div>
                        </td>
                        <td class="break-word">
                            <p class="fz11 text-over-2 mr0 table-note lh18" title="{{ $review->content }}" style="padding-right: 30px;">
                                {{ $review->content }}
                            </p>
                        </td>
                        <td class="fz13 hidden-xs">
                            @if (array_key_exists($review->source, $reviewSources))
                                {{ $reviewSources[$review->source] }}
                            @else
                                {{$reviewSources['aliexpress']}}
                            @endif
                        </td>
                        <td class="hidden-xs">
                            <div style="display: flex;">
                                @if(!empty($review->img) && is_array($review->img))
                                    @foreach(array_slice($review->img, 0, 2) as $img)
                                        <img src="{{ $review->source == "web" ? str_replace(session('shopId'),session('shopId').'/_thumb',$img)  : $img }}" data-jslghtbx="{{ $review->source == "web" ? str_replace(session('shopId'),session('shopId').'/_thumb',$img)  : $img }}" alt="" class="img-rounded mar-r-3 jslghtbx-thmb" data-jslghtbx-group="thmb-group-{{ $review->id }}" width="34px" height="34px" style="object-fit: cover">
                                    @endforeach

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
                                                            <img src="{{ $review->source == "web" ? str_replace(session('shopId'),session('shopId').'/_thumb',$img)  : $img }}" data-jslghtbx="{{ $review->source == "web" ? str_replace(session('shopId'),session('shopId').'/_thumb',$img)  : $img }}" data-jslghtbx-group="thmb-group-{{ $review->id }}" class="jslghtbx-thmb" width="34px" height="34px" style="object-fit: cover">
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </td>
                        <td class="fz13 hidden-xs">
                            {{ date('d-m-Y', strtotime($review->created_at))}}
                        </td>
                        <td>
                            <div class="button--group">
                                <button class="button button--default button--icon pinReview {{ (isset($review->pin) &&  !empty($review->pin) ) ? 'active' : '' }} {{ (isset($shopPlanInfo['pin']) &&  !empty($shopPlanInfo['pin'])) ? '' : 'disabled' }}" {{ (isset($shopPlanInfo['pin']) && !empty($shopPlanInfo['pin'])) ? '' : 'disabled' }}
                                        data-comment_id="{{ $review->id }}"
                                        data-type="{{ (isset($review->pin) && !empty($review->pin)) ? 'unpin' : 'pin' }}"
                                        data-token="{{ csrf_token() }}"
                                        data-toggle="tooltip" title="{{(isset($review->pin) && empty($shopPlanInfo['pin'])) ? 'Only available in Premium version' : ( (isset($review->pin) && !empty($review->pin)) ? 'Unpin' : 'Pin to top') }}"
                                        >
                                    <i class="icon material-icons">flag</i>
                                </button>
                                <button class="button button--default button--icon _btnEdit editReview" data-country="{{$review->country}}" data-comment_id="{{ $review->id }}" data-toggle="tooltip" title="Edit">
                                    <i class="icon material-icons" style="font-size: 18px;">border_color</i>
                                </button>
                                <button class="button button--default button--icon deleteReview" data-comment_id="{{ $review->id }}" data-toggle="tooltip" title="Delete">
                                    <i class="icon material-icons">delete</i>
                                </button>
                            </div>
                        </td>
                    </tr>

                @endforeach
                <input type="hidden" name="product_id" value="{{$product->id}}"/>
            </tbody>
        </table>
    </div>
    <div class="text-right text-center-md" style="margin-top: -5px;">
        {{ $listReview->appends($filters)->links('vendor.pagination.alireview') }}
    </div>
@else
    @include('partials/empty_product_review')
@endif
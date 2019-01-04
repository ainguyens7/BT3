<div class="product-star-preview mar-b-25 bg-white pad-15">
    <div class="row">
        <div class="col-md-7">
            <!-- Media Object Point -->
            <div class="media media--point flex-align-center-md">
                <div class="media-left media-middle">
                    <img src="{{ isset($product->image) ? $product->image : cdn('images/avatar.jpg') }}" style="width: 120px; border: solid 1px #e9e9e9;" class="img-rounded" alt="{{ $product->title }}">
                </div>

                <div class="media-body ">
                    <p class="fz13 fw500 mar-t-0 mar-b-10 color-grey-800 text-over-2" style="padding-right: 20px;">{{ $product->title }}</p>

                    <div class="p-relative">
                        <div class="media-body_rating mar-b-15" style="display: flex; align-items: center;">
                            @php
                                $review_count = $statistic['total_star'] != 1 ?  config('settings.translate.text_reviews_title') :  config('settings.translate.text_review_title');
                            @endphp
                            <input type="hidden" class="alr-rating alr-rating-{{$product->id}}" data-readonly data-filled="alr-icon-star" data-empty="alr-icon-star" data-fractions="3" value="{{ $statistic['avg_star']}}" />
                            <span class="fz13 color-grey-800 mar-l-5">(Base on <span class="product-item-total-review">{{ $statistic['total_star'] }}</span> {{ $review_count }})</span>
                        </div>

                        <div class="row mar-t-10">
                            <div class="col-lg-12 text-left">
                                <button class="button button--primary add-reviews-btn-{{ $product->id }}" @click.prevent="showModal({{ json_encode($product) }})" data-content="">{{ __('reviews.label_add_review') }}
                                </button>
                                <input type="hidden" id="get-review-settings" value="{{ $shopMeta }}">
                                <input type="hidden" id="get-review-shop-id" value="{{ session('shopId') }}">
                                <input type="hidden" id="all-country" value="{{ $allCountry }}">
                                @include('includes/modal_waiting')
                                @include('products.modal_get_reviews')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: Media Object Point -->
        </div>

        <div class="col-md-5">
            <ul class="star-process list-unstyled">
                @for($i = 5; $i >= 1; $i--)
                    @include('partials/manage_review_product_summary_review_progress_bar',
                    [
                        'rating_title' => $i,
                        'rating_base_value' => $statistic["total_star_{$i}"],
                        'rating_total_value' => $statistic['total_star']
                    ])
                @endfor
            </ul>
        </div>
    </div>
</div>


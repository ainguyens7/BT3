@extends('layout.dashboard',['page_title' => 'Manage Reviews'])

@section('breadcrumb')
    @include('partials/breadcrumb', ['breadcrumb_link' => 'Manage Reviews'])
@endsection

@section('body_content')
    <div class="wrapper-space dashboard-manage-reviews-page" id="page-list-product-get-review">
        <!-- Product heading -->
        <div class="wrapper-tbl-action" style="padding-bottom: 10px; padding-left: 26px;">
            @include('includes/search_product_form')
        </div>
        <!-- END: Product heading -->

        <!-- Product list -->
        @if (! $listProduct->isEmpty())
            <div class="table-responsive-xxs">
                <!--  table-head-light -->
                <table class="table table-hover table-ali-custom table-mid table-pad-25 mar-b-15" id="table-select-all">
                    <thead>
                        <tr>
                            <th>{{ __('products.label_table_products_name') }}</th>
                            <th width="15%">{{ __('products.label_table_reviews') }}</th>
                            <th width="15%">{{ __('products.label_table_bulk_settings') }}</th>
                            <th width="19%" style="min-width: 210px;">{{ __('products.label_table_action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($listProduct as $k=> $product)
                            <tr id="product-item-{{ $product->id }}">
                                <td class="break-word">
                                    <div class="media">
                                        <div class="media-left media-middle">
                                            <img src="{{ !empty($product->image) ? $product->image : cdn('images/avatar.jpg') }}" class="media-object img-rounded" style="width:50px; height: 50px;" alt="{{ $product->title }}">
                                        </div>

                                        <div class="media-body">
                                            <h4 class="media-heading fz13 fw500 p-realative mar-b-0">
                                                <a href="{{ route('reviews.product',['product' => $product->id]) }}">{{ $product->title }}</a>
                                            </h4>
                                        </div>
                                    </div>
                                </td>
                                <td class="td-rating">
                                    <input type="hidden" class="alr-rating alr-rating-{{$product->id}}" data-readonly data-filled="alr-icon-star" data-empty="alr-icon-star" data-fractions="3" value="{{ $product->avg_reviews }}"/>
                                    <p class="color-grey-800 fz13 mar-0">
                                        {!!  \App\Helpers\Helpers::getStarRating($product->avg_reviews)  !!}
                                        @if(intval($product->total_reviews) !== 1)
                                            <span class="icon-icon_chat-room mar-r-3 fz13">{!! __('products.summary_reviews_product', ['review' =>  $product->total_reviews]) !!}</span>
                                        @else
                                            <span class="icon-icon_chat-room mar-r-3 fz13">{!! __('products.summary_review_product', ['review' =>  $product->total_reviews]) !!}</span>
                                        @endIf
                                    </p>
                                </td>
                                <td>
                                    @if($product->total_reviews)
                                    <select class="form-control select2 unsearch selectActionAllReviews2" data-product_id="{{ $product->id }}">
                                        <option value>Action</option>
                                        <option value="publishAllReviewsModal">Publish</option>
                                        <option value="unPublishAllReviewsModal">Unpublish</option>
                                        <option value="deleteAllReviewsModal">Delete</option>
                                    </select>
                                    @endif
                                </td>
                                <td>
                                    <button class="button button--primary add-reviews-btn-{{ $product->id }}" @click.prevent="showModal({{ json_encode($product) }})" data-content="">
                                        {{ __('reviews.label_add_review') }}
                                    </button>

                                    <a href="https://{{ session('shopDomain','') }}/products/{{ $product->handle }}" class="button button--default-pink" target="_blank" style="padding-left: 8px; padding-right: 8px;">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="text-right text-center-md">
                <nav>
                    {{ $listProduct->appends($filter)->links('vendor.pagination.alireview') }}
                </nav>
            </div>
            <!-- END: Pagination -->
        @else
            <div class="product-search-no-review">
                <form id="form_update_products" method="post" action="{{route('cs.updateProductsCS')}}" >
                    <div class="empty-space">
                        <i class="material-icons">speaker_notes_off</i> <br>

                        @if(empty($filter))
                            <div>If your products on Shopify aren't showing up, please click <strong>Update button</strong>  below to sync product list</div>
                            <button type="submit" class="button button--primary setting-submit-btn mar-t-30">
                                {{__('cs.button_update_products')}}
                            </button>
                            @else
                            <div>{{__('reviews.text_no_search_result')}}</div>
                            <br><br>
                        @endif
                    </div>
                    {!! csrf_field() !!}
                </form>
            </div>
        @endif
        <!-- END: Product list -->
        <input type="hidden" id="get-review-settings" value="{{ $shopMeta }}">
        <input type="hidden" id="get-review-shop-id" value="{{ isset($shopInfo['shop_id']) ? $shopInfo['shop_id'] : '' }}">
        <input type="hidden" id="all-country" value="{{ $allCountry }}">
        <input type="hidden" id="shop-current-is-reviews-app" value="{{ isset($shopInfo['is_review_app']) ? $shopInfo['is_review_app'] : '' }}">

        @include('includes/modal_waiting')
        @include('products.modal_get_reviews')
    </div>

    @include('layout.extension')

    @include('products.modal_confirm_delete_allReviews')
    @include('products.modal_confirm_delete_all')
    @include('products.modal_confirm_publish_all')
    @include('products.modal_confirm_unpublish_all')
    @include('reviewsBackend.modal_upgrade_pro')
@endsection
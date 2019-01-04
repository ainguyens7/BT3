<form method="GET" action="{{ route('products.list') }}" class="search-product-form">
    @if(intval($countProduct) > 0)
        <span class="fz13 fw600 total-product pull-left" style="vertical-align: -webkit-baseline-middle;">
            {!! __('products.label_total_products', compact('countProduct')) !!}
        </span>
    @else
        <span class="fz13 fw600 total-product pull-left" style="vertical-align: -webkit-baseline-middle;">
            {!! __('products.label_total_product', compact('countProduct')) !!}
        </span>
    @endIf

        @if(!empty($countReviews))
            <div class="input-group input-group--search input-group--left pull-left mar-l-15" style="width: 200px">
                <select name="delete-shop-reviews" id="selectActionDeleteAllReviews" class="form-control select2 unsearch">
                    <option value>{{ __('products.label_delete_product_reviews') }}</option>
                    <option value="customer" data-title="{{ __('products.label_delete_customer_reviews_title') }}">{{ __('products.label_delete_customer_reviews') }}</option>
                    <option value="imported" data-title="{{ __('products.label_delete_imported_reviews_title') }}">{{ __('products.label_delete_imported_reviews') }}</option>
                    <option value="all" data-title="{{ __('products.label_delete_all_reviews_title') }}">{{ __('products.label_delete_all_reviews') }}</option>
                </select>
            </div>
        @endIf

    <button type="submit" class="button button--primary pull-right w-130px">
        {{ __('products.cta_filter_apply') }}
    </button>

    <div class="action-group form-group pull-right mar-r-15" style="width: 135px;">
        <select name="is_review" class="form-control select2 unsearch fz13">
            <option value="">{{ __('products.label_filter_all_products') }}</option>
            <option value="1" {{ request('is_review', '') == '1' ? 'selected' : '' }}>{{ __('products.label_filter_is_reviews') }}</option>
            <option value="-1" {{ request('is_review', '') == '-1' ? 'selected' : '' }}>{{ __('products.label_filter_is_no_reviews') }}</option>
        </select>
    </div>

    <div class="input-group input-group--search input-group--left pull-right">
        <input type="text" name="title" class="form-control bd-l-0 pad-l-3 fz13"
               value="{{ request('title', '') != '' ? request('title') : '' }}"
               placeholder="{{ __('products.label_filter_enter_keyword') }}">
        <span class="input-group-btn">
            <button class="button button--default button--icon mar-0 bd-r-0" type="submit">
                <i class="icon material-icons">search</i>
            </button>
        </span>
    </div>

    <div class="clearfix"></div>
</form>
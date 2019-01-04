<div class="wrapper-tbl-action wrapper-tbl-action-1">
    {{-- <a href="{{ route('products.list') }}" class="button button--default _btn-back pull-left">
        <i class="material-icons mar-r-5 color-grey-400">keyboard_arrow_left</i>
        <span class="fz13">{{ __('reviews.label_back') }}</span>
    </a> --}}

    <a href="https://{{ session('shopDomain','') }}/products/{{ $product->handle }}" target="_blank" class="button button--primary pull-right">
        {{ __('reviews.title_view_product') }}
    </a>

    @if($listReview->total())
    <div class="wrap-select-action pull-right">
        <select id="selectActionAllReviews" class="form-control select2 unsearch pull-right">
            <option value>Select action </option>
            <option value="publish">Publish All </option>
            <option value="unpublish">Unpublish All</option>
            <option value="delete">Delete All </option>
        </select>
    </div>
    @endif

    @if($listReview->total())
    <button class="button  pull-right mar-r-15" id="add_schema_google_rating" productID="{{ $product->id }}">
        {{ __('reviews.title_add_google_rating') }}
    </button>
    @endif

    <div class="clearfix"></div>
</div>
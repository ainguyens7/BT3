@if($listReview->total())
<div class="wrapper-tbl-action pad-l-30">
    <label class="wrap-custom-box d-inline-block pad-l-35 fz13 fw600">
        <input type="checkbox" id="review-approve-select-all">
        <strong id="result-total-reviews-checked" style="display: inherit;" class="fw600">{!! __('reviews.label_select_all_review', ['total' => $listReview->total()]) !!}</strong>
        <span class="checkmark-ckb"></span>
    </label>

    <div class="status-action-wrap action" style="display: none">
        <select class="form-control select2 unsearch pending-reviews-action" data-token ="{{ csrf_token() }}">
            <option value="">{{ __('reviews.option_select_option') }}</option>
            <option value="publish">{{ __('reviews.title_approve_all') }}</option>
            <option value="unpublish">{{ __('reviews.title_remove_all') }}</option>
            {{-- <option value="delete">{{ __('reviews.text_delete') }}</option> --}}
        </select>
    </div>

    <label id="slc_all_reviews_prod_wrap">
        <a class="slc-all-reviews-prod-text">Select All Reviews</a>
        <input type="checkbox" id="ckc-all-reviews-prod" type-action="approve">
        <input type="hidden" id="product-uncheck-current-page">
        <input type="hidden" name="total-all-reviews-prod" value="{{$listReview->total()}}">
    </label>

    {{-- <div class="status-action-wrap review-action-wrap" style="display: none">
        <button class="button button--primary fw600 pull-right w-130px remove-all-btn mar-l-15" data-token ="{{ csrf_token() }}">
            {{ __('reviews.title_remove_all') }}
        </button>

        <button class="button button--default fw600 pull-right w-130px approve-all-btn color-grey-400" data-token ="{{ csrf_token() }}">
            {{ __('reviews.title_approve_all') }}
        </button>
    </div> --}}

    <div class="clearfix"></div>
</div>
@endif
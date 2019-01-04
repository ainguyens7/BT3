@if($listReview->total() || !empty($_GET))
    <div class="wrapper-tbl-action wrapper-tbl-action-2">
        @if($listReview->total())
            <label class="wrap-custom-box pad-l-35 pull-left mar-r-15 fz13 fw500" style="margin-top: 7px;">
                <input id="review-approve-select-all" type="checkbox">
                <strong id="result-total-reviews-checked" style="display: inherit;" class="fw600">{!! __('reviews.label_select_all_review', ['total' => $listReview->total()]) !!}</strong>
                <span class="checkmark-ckb"></span>
            </label>
        
            <div class="status-action-wrap pull-left" style="display: none">
                <select name="actionReview" id="actionReview" data-token="{{ csrf_token() }}" class="select2 unsearch">
                    <option value="">{{ __('reviews.option_select_option') }}</option>
                    <option value="publish">{{ __('reviews.text_publish') }}</option>
                    <option value="unpublish">{{ __('reviews.text_unpublish') }}</option>
                    <option value="delete">{{ __('reviews.text_delete') }}</option>
                </select>
            </div>

            <label id="slc_all_reviews_prod_wrap">
                <a class="slc-all-reviews-prod-text">Select All Reviews</a>
                <input type="checkbox" id="ckc-all-reviews-prod">
                <input type="hidden" id="product-uncheck-current-page">
                <input type="hidden" name="total-all-reviews-prod" value="{{$listReview->total()}}">
            </label>
        @endif

        <form method="get">
            <div class="filter-product-review pull-right mar-l-15">
                <select name="star[]" class="form-control fillter-reviews multiselect" multiple="multiple" title="All star">
                    <option value="5" {{ request('star', '') !== '' && in_array(5, request('star')) ? 'selected' : '' }}>
                        5 {{ __('reviews.text_stars') }}</option>
                    <option value="4" {{ request('star', '') !== '' && in_array(4, request('star'))? 'selected' : '' }}>
                        4 {{ __('reviews.text_stars') }}</option>
                    <option value="3" {{ request('star', '') !== '' && in_array(3, request('star')) ? 'selected' : '' }}>
                        3 {{ __('reviews.text_stars') }}</option>
                    <option value="2" {{ request('star', '') !== '' && in_array(2, request('star')) ? 'selected' : '' }}>
                        2 {{ __('reviews.text_stars') }}</option>
                    <option value="1" {{ request('star', '') !== '' && in_array(1, request('star')) ? 'selected' : '' }}>
                        1 {{ __('reviews.text_star') }}</option>
                </select>
            </div>

            <div class="filter-review-source pull-right mar-l-15">
                <select name="source" id="review-source" class="form-control select2 unsearch select-review-source fillter-reviews">
                    <option value='all'>All Sources</option>
                    @foreach($reviewSources as $key => $value)
                        <option value="{{$key}}" {{ request('source', '') !== '' && request('source') === $key ? 'selected' : '' }}>{{$value}}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-review-status pull-right mar-l-15">
                <select name="status" id="" class="form-control select2 unsearch select-collection fillter-reviews">
                    <option value="">{{ __('reviews.label_all_status') }}</option>
                    <option value="publish" {{ request('status', '') !== '' && request('status') == 'publish' ? 'selected' : '' }}>{{ __('reviews.text_published') }}</option>
                    <option value="unpublish" {{ request('status') !== '' && request('status') == 'unpublish' ? 'selected' : '' }}>{{ __('reviews.text_unpublished') }}</option>
                </select>
            </div>

            <div class="input-group input-group--search input-group--left pull-right">
                <input type="text" class="form-control bd-l-0 pad-l-3 fz13" placeholder="{{ __('reviews.text_keyword') }}" name="keyword" value="{{ request('keyword', '') !== '' ? request('keyword') : '' }}">
                <span class="input-group-btn">
                    <button type="submit" class="button button--default button--icon mar-0 bd-r-0" type="button">
                        <i class="icon material-icons">search</i>
                    </button>
                </span>
            </div>
            <div class="clearfix"></div>
        </form>
    </div>
@endif


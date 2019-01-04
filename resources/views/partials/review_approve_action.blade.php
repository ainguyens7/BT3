<div class="wrapper-tbl-action pad-l-30">
    <label class="wrap-custom-box d-inline-block pad-l-35 fz13 fw600">
        {{ __('reviews.label_select_all_review') }}
        <input type="checkbox">
        <span class="checkmark-ckb"></span>
    </label>

    <select class="form-control">
        <option value="">{{ __('reviews.option_select_option') }}</option>
        <option value="publish">{{ __('reviews.text_publish') }}</option>
        <option value="unpublish">{{ __('reviews.text_unpublish') }}</option>
    </select>

    <button class="btn btn-primary fz12 fw-bold pull-right w-130px">
        {{ __('reviews.title_hide_all') }}
    </button>

    <button class="btn btn-default fz12 btn-hvr-pink fw-bold pull-right mar-r-15 color-grey-400 w-130px">
        {{ __('reviews.title_remove_all') }}
    </button>

    <div class="clearfix"></div>
</div>
<div class="alireview-add-form">
    {{--<div class="alireview-form-title">{{ $translate['title_form'] }}</div>--}}
    <form role="form" id="add_form_review" class="alireview-form" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="alireview_shop_id" value="{{ isset($shop_id) ? $shop_id : '' }}">
        <input type="hidden" name="alireview_product_id" value="{{ isset($product_id) ? $product_id : '' }}">
        <input type="hidden" name="alireview_approve_review" value="{{ isset($approve_review) ? $approve_review : 1 }}">
        <input type="hidden" name="alireview_country_code" value="US">
        <div class="alireview-form-group mar-t-0">
            <label>{{ $translate['label_name'] }} </label>
            <input type="text" name="alireview_author" class="alireview-input-text" id="your_name" autocomplete="off"
                   >
            {{-- <label class="err err-author"></label> --}}
        </div>
        <div class="alireview-form-group">
            <label>{{ $translate['label_email'] }}</label>
            <input type="text" class="alireview-input-text" name="alireview_email" id="your_email" autocomplete="off"
                   >
            <label class="err err-email"></label>
        </div>
        <div class="alireview-form-group rating-star">
            <label class="field-title">{{ $translate['label_your_rating'] }}:</label>
            <div class="box-rating">
                <div class="alireview-status">
                    <input type="hidden" name="alireview_star" value="5" class="alr-rating" data-filled="alr-icon-star" data-empty="alr-icon-star" data-fractions="1" id="value-add-review-star"/>
                    <div id="add-alireview-star"></div>
                </div>
            </div>
            {{-- <label class="err err-star"></label> --}}
        </div>
        <div class="alireview-form-group">
            <textarea class="alireview-input-textarea" rows="5" name="alireview_content" id="content"
                      placeholder="{{ isset($translate['text_write_comment']) ? $translate['text_write_comment'] : '' }}" ></textarea>
            {{-- <label class="err err-content"></label> --}}
        </div>

        <div class="alireview-form-group" style="position: relative">
            <div class="alireview-file-upload-wrap">
                <div class="alireview_loading_upload">
                    <img src="{{ cdn('images/loading-small.svg') }}">
                </div>
                
                <label for="alireview_file_upload" class="alireview-form-control alireview-file-upload-label">
                    <span class="alireview-file-upload-btn">{{ $translate['label_image'] }}</span>
                    <span class="alireview-file-name"></span>
                </label>

                <input type="file" name="file" id="alireview_file_upload" accept="image/x-png,image/gif,image/jpeg" multiple>
            </div>
        </div>
        <div class="alireview-form-group">
            <ul class="alireview_list_image"></ul>
        </div>

        <div class="btn-alireview-wrap">
            <button type="submit" id="btn-add-review" class="btn-submit-review">{{ $translate['button_submit'] }}</button>
        </div>
    </form>
</div>

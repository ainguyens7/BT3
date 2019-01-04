<div class="alireview-modal-inline" id="alireview-modal-add-review">
    <div class="alireview-modal-add-review">
        <div class="alireview-modal-add-review-content">
            <form role="form" id="add_form_review" class="alireview-form" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="alireview_shop_id" value="{{ isset($shop_id) ? $shop_id : '' }}">
                <input type="hidden" name="alireview_product_id" value="{{ isset($product_id) ? $product_id : '' }}">
                <input type="hidden" name="alireview_approve_review"
                       value="{{ isset($approve_review) ? $approve_review : 1 }}">
                <input type="hidden" name="alireview_country_code" value="US">
                <div class="alireview-form-group">
                    <label for="name">{{ $translate['label_name'] }}<i class="alireview-input-required">*</i></label>
                    <input type="text" name="alireview_author" id="name"
                           class="alireview-form-control">
                    <label class="err err-author"></label>
                </div>
                <div class="alireview-form-group">
                    <label for="email">{{ $translate['label_email'] }} ({{ $translate['optional'] }})</label>
                    <input type="text" id="email" name="alireview_email"
                           class="alireview-form-control">
                    <label class="err err-email"></label>
                </div>
                <div class="alireview-form-group alireview-form-group-inline">
                    <label>{{ $translate['label_your_rating'] }}: </label>
                    <div class="alireview-rating">
                        <input type="hidden" name="alireview_star" value="0" class="alr-rating" data-filled="alr-icon-star" data-empty="alr-icon-star" data-fractions="3" id="value-add-review-star"/>
                    <div id="add-alireview-star"></div>
                    </div>
                    <label class="err err-star"></label>
                </div>
                <div class="alireview-form-group">
                    <textarea name="alireview_content" id="" cols="30" rows="5"
                              class="alireview-form-control"
                              data-alireview-keypress="true"
                              data-alireview-keypress-target="#alireview-on-keypress"
                              maxlength="2000" placeholder="{{ isset($translate['text_write_comment']) ? $translate['text_write_comment'] : '' }}"></textarea>
                    {{--<textarea  name="alireview_content" cols="30" rows="8" class="alireview-form-control"></textarea>--}}
                    {{--<span class="alireview-text-muted" id="alireview-on-keypress"><span>200</span> characters left</span>--}}
                    <label class="err err-content"></label>
                </div>

                <div class="alireview-form-group" style="position: relative">
                    <label>{{ $translate['label_image'] }}</label>
                    <div class="alireview-file-upload-wrap">
                        <div class="alireview_loading_upload">
                            <img src="{{ cdn('images/loading-small.svg') }}">
                        </div>
                        <label for="alireview_file_upload"
                               class="alireview-form-control alireview-file-upload-label"><span
                                    class="alireview-file-upload-btn">{{ $translate['choose_file'] }}</span><span
                                    class="alireview-file-name"></span></label>
                        <input type="file" id="alireview_file_upload" accept="image/x-png,image/gif,image/jpeg"
                               multiple>
                    </div>
                </div>
                <div class="alireview-form-group">
                    <ul class="alireview_list_image"></ul>
                </div>

                <button type="submit" id="btn-add-review"
                        class="btn-submit-review alireview-button">{{ $translate['button_submit'] }}</button>
            </form>
        </div>
    </div>
</div>


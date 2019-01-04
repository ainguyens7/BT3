<!-- Publish All Reviews Modal-->
<div id="publishAllReviewsModal" class="modal fade ali-modal ali-modal--noline" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content text-center">
            <div class="modal-body pad-0">
                <div class="modal-body__content fw600 fz13">
                    <p class="note fw600 mar-b-35">{{ __('reviews.text_publish_all_review') }}</p>
                </div>
            </div>

            <div class="modal-footer text-center">
                <form id="formPublishAllReview2">
                    <button type="submit" class="button button--primary mar-r-5">Publish All</button>
                    <button type="button" class="button button--default w-130px mar-l-5" data-dismiss="modal">Cancel</button>

                    <input type="hidden" value="{{ csrf_token() }}" name="_token">
                    <input type="hidden" value="" name="product_id">
                </form>
            </div>
        </div>
    </div>
</div>
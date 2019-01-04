<!-- Un Publish All Reviews Modal-->
<div id="unPublishAllReviewsModal" class="modal fade ali-modal ali-modal--noline" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body text-center pad-0">
                <div class="modal-body__content fz13">
                    <p class="note mar-b-35 fw600">{!! __('reviews.text_unpublish_all_review') !!}</p>
                </div>
            </div>

            <div class="modal-footer text-center">
                <form id="formUnPublishAllReview2">
                    <button type="submit" class="button button--primary mar-r-5">Unpublish All</button>
                    <button type="button" class="button button--default w-130px mar-l-5" data-dismiss="modal">Cancel</button>

                    <input type="hidden" value="{{ csrf_token() }}" name="_token">
                    <input type="hidden" value="" name="product_id">
                </form>
            </div>
        </div>
    </div>
</div>
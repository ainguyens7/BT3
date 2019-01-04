<!-- Delete Reviews Modal-->
<div id="publishAllReviewsModal" class="modal fade ali-modal ali-modal--noline" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body text-center pad-0">
                <form id="formPublishAllReview">
                    <div class="delete-reviews-wrap">
                        <p class="mar-b-35 fw600">{{ __('reviews.text_publish_all_review') }}</p>
                        <div class="delete-reviews-btn-wrap">
                            <button type="submit" class="button button--primary w-130px ars-btn mar-r-5">Publish All</button>
                            <button type="button" class="button button--default w-130px ars-btn-o mar-l-5" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                    <input type="hidden" value="{{ csrf_token() }}" name="_token">
                    <input type="hidden" value="{{ $product->id }}" name="product_id">
                </form>
            </div>
        </div>
    </div>
</div>
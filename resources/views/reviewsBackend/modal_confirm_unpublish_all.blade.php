<!-- Delete Reviews Modal-->
<div id="unPublishAllReviewsModal" class="modal fade ali-modal ali-modal--noline" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body text-center pad-0">
                <form id="formUnPublishAllReview">
                    <div class="delete-reviews-wrap">
                        <p class="mar-b-35 fw600">{!! __('reviews.text_unpublish_all_review') !!}</p>
                        <div class="delete-reviews-btn-wrap">
                            <button type="submit" class="button button--primary mar-r-5 ars-btn">Unpublish All</button>
                            <button type="button" class="button button--default mar-l-5 w-130px ars-btn-o" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                    <input type="hidden" value="{{ csrf_token() }}" name="_token">
                    <input type="hidden" value="{{ $product->id }}" name="product_id">
                </form>
            </div>
        </div>
    </div>
</div>
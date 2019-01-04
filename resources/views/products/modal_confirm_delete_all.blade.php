<!-- Delete Reviews Modal-->
<div id="deleteAllReviewsModal" class="modal fade ali-modal ali-modal--noline" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content text-center">
            <div class="modal-body pad-0">
                <div class="modal-body__content fz13">
                    <p class="note fw600 mar-b-35">{{ __('reviews.text_delete_all_review') }}</p>
                </div>
            </div>

            <div class="modal-footer">
                <form id="formDeleteAllReview2" class="text-center">
                    <button type="submit" class="button button--primary mar-r-5">Delete All</button>
                    <button type="button" class="button button--default w-130px mar-l-5" data-dismiss="modal">Cancel</button>

                    <input type="hidden" value="{{ csrf_token() }}" name="_token">
                    <input type="hidden" value="" name="product_id">
                </form>
            </div>
        </div>
    </div>
</div>
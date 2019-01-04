<!-- Delete Reviews Modal-->
<div id="deleteMulTiReviewsModal" class="modal fade ali-modal ali-modal--noline" role="dialog">
    <div class="modal-dialog" style="max-width: 400px;">
        <!-- Modal content-->
        <div class="modal-content" style="padding: 35px;">
            <div class="modal-body pad-0">
                <form id="formDeleteMultiReview">
                    <div class="delete-reviews-wrap text-center">
                        <p class="mar-b-35 fw600">{{ __('reviews.text_delete_selected_review') }}</p>
                        <div class="delete-reviews-btn-wrap">
                            <button type="submit" class="button button--primary ars-btn mar-r-5" autofocus>Delete</button>
                            <button type="button" class="button button--default ars-btn-o mar-l-5" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                    <input type="hidden" value="{{ csrf_token() }}" name="_token">
                </form>
            </div>
        </div>
    </div>
</div>
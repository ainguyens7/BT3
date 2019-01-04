<!-- Delete Reviews Modal-->
<div id="deleteReviewsModal" class="modal fade ali-modal ali-modal--noline" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content text-center">
            <div class="modal-body pad-0">
                <form id="formDeleteReview">
                    <div class="delete-reviews-wrap">
                        <p>{{ __('reviews.text_delete_review') }}</p>
                        <div class="delete-reviews-btn-wrap mar-t-25">
                            <button type="submit" class="button button--primary ars-btn mar-r-5" autofocus>DELETE</button>
                            <button type="button" class="button button--default ars-btn-o mar-l-5" data-dismiss="modal">CANCEL</button>
                        </div>
                    </div>
                    <input type="hidden" value="{{ csrf_token() }}" name="_token">
                    <input type="hidden" value="0" name="comment_id">
                </form>
            </div>
        </div>
    </div>
</div>
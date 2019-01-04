<!-- Delete Reviews Modal-->
<div id="hideAllReviewsModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="demo-icon icon-cancel-4"></i></button>
            </div>
            <div class="modal-body">
                <form id="formHideAllReview">
                    <div class="delete-reviews-wrap">
                        <p>Are you sure you want to hide all these reviews?</p>
                        <div class="delete-reviews-btn-wrap">
                            <button type="submit" class="ars-btn">HIDE ALL</button>
                            <button type="button" class="ars-btn-o" data-dismiss="modal">CANCEL</button>
                        </div>
                    </div>
                    <input type="hidden" value="{{ csrf_token() }}" name="_token">
                </form>
            </div>
        </div>
    </div>
</div>
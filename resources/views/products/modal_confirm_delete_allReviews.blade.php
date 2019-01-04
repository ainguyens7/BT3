<!-- Delete Reviews Modal-->
<div id="deleteAllReviewsShopModal" class="modal fade ali-modal ali-modal--noline" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content text-center">
            <div class="modal-body pad-0">
                <div class="modal-body__content fz13">
                    <p class="note fw600 mar-b-35 title"></p>
                </div>
            </div>

            <div class="modal-footer">
                <form id="formDeleteAllReviewShop" class="text-center">
                    <button type="submit" class="button button--primary mar-r-5">Delete all</button>
                    <button type="button" class="button button--default w-130px mar-l-5" data-dismiss="modal">Cancel</button>

                    <input type="hidden" value="{{ csrf_token() }}" name="_token">
                    <input type="hidden" value="" name="action">
                </form>
            </div>
        </div>
    </div>
</div>
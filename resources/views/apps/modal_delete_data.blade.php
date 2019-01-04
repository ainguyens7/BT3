<!-- Delete Reviews Modal-->
<div id="deleteDataModal" class="modal fade ali-modal ali-modal--noline" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body pad-0">
                <form id="formDeleteData">
                    <div class="delete-reviews-wrap text-center">
                        <p class="change-text-plan fz14 fw600 text-cener mar-0">All your data will be deleted if you downgrade.</p>

                        <span>For payment support, please follow this link: <a href="http://bit.ly/2Mx43kl" target="_blank">http://bit.ly/payment_term</a></span>
                        <div class="delete-reviews-btn-wrap text-center" style="margin: 30px 0 0">
                            <button type="submit" class="button button--primary mar-r-5 ars-btn vertical-middle button-downgrade" style="margin-right: 8px;">Downgrade</button>
                            <button type="button" class="button button--default mar-l-5 ars-btn-o w-130px vertical-middle" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                    <input type="hidden" value="{{ csrf_token() }}" name="_token">
                    <input type="hidden" value="" name="plan">
                </form>
            </div>
        </div>
    </div>
</div>
@if (isset($randomPopup) && $randomPopup != NULL)
<div id="{{ $randomPopup['modal_id'] }}" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <div class="deals-popup-wrap">
                    <button type="button" class="close" data-dismiss="modal" ><i class="demo-icon icon-cancel-4" id="{{ $randomPopup['close_id'] }}"></i></button>
                    <a id="showPopupPromotion" class="figure-feature-popup" id="figure-feature-popup">
                        <img src="{{ URL::asset($randomPopup['resource']) }}" alt="Campaign convert free to pro">
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endif
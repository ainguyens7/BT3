<!-- Delete Reviews Modal-->
<div id="modal-deals-popup" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <div class="deals-popup-wrap" style="width: 28vw;">
                    <button type="button" class="close" data-dismiss="modal"><i class="demo-icon icon-cancel-4"></i></button>
                    <a href="{{ route('apps.upgrade') }}">
                        <img src="{{ URL::asset('images/backend/deals-popup.png') }}" alt="">
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
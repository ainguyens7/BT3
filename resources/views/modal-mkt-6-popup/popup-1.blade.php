<link href="https://fonts.googleapis.com/css?family=Poppins:400,500,500i,600,700,800" rel="stylesheet">
<!-- POP-UP #1 -->
<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#popup-1">Popup 1</button> -->

<div class="modal fade spopup-mkt spopup-mkt__style-1" tabindex="-1" role="dialog" id="popup-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> --}}
            </div>
            <div class="modal-body ff-poppins">
                <p class="fz17 fw700" style="margin-bottom: 15px;margin-top: 15px;">DOING DROPSHIPPING?</p>
                <p class="fz17 pumkt-description fw500">
                    Let's hide
                    <span class="mkfix-logo">
                        <img src="{{ url('images/backend/popup-mkt/alireview-icon-install-extension.png') }}">
                        <span>Powered By Ali Reviews</span>
                    </span>
                    <br class="hidden-lg hidden-md"> and get tons <br class="hidden-xs hidden-sm"> more features with
                    <span class="fw700 mkt-1-fo">JUST 3 CUPS OF COFFEE</span>
                </p>
                <img src="{{ url('images/backend/popup-mkt/arrow-popup-1.png') }}" class="pu1-arrow">
                <form method="post" action="{{ route('apps.upgradeHandle') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="plan" value="premium">
                    <button type="submit" class="fw600">Good for me, let's get some coffee!</button>
                </form>
                <p class="fw500i fz15 pumkt-sub-link"><a href="javascript:void(0)" data-dismiss="modal">I'm ok with that logo</a></p>
            </div>
        </div>
    </div>
</div>
<!-- END: POP-UP #1 -->
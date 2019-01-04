<link href="https://fonts.googleapis.com/css?family=Titillium+Web:300,400,600,700" rel="stylesheet">
<!-- POP-UP #4 -->
<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#popup-4">Popup 4</button> -->

<div class="modal fade spopup-mkt spopup-mkt__style-4" tabindex="-1" role="dialog" id="popup-4" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> --}}
            </div>
            <div class="modal-body pumkt-4 text-center">
                <div class="pumkt-4__description fz20">
                    STILL HAVE SOME MORE <br>
                    PRODUCTS TO GET REVIEWS?<br>
                    <span>DON'T WORRY</span>, HOW ABOUT WE  <br>
                    GIVE YOU UNLIMITED PRODUCTS <br>
                    <span>FOR JUST 3 BEERS?</span>
                </div>

                <div class="pumkt-4__yes">
                    <form method="post" action="{{ route('apps.upgradeHandle') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="plan" value="premium">
                        <button type="submit" class="fz17fw600">Good for me, let's get some beers!</button>
                    </form>
                </div>

                <div class="pumkt-4__buttn-no">
                    <a href="javascript:void(0)" class="ani-space-1px" data-dismiss="modal"> No, 10 is enough for now</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: POP-UP #4 -->
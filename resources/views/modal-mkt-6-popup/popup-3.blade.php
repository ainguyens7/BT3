<link href="https://fonts.googleapis.com/css?family=Titillium+Web:300,400,500,600,700" rel="stylesheet">
<!-- POP-UP #3 -->
<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#popup-3">Popup 3</button> -->

<div class="modal fade spopup-mkt spopup-mkt__style-3" tabindex="-1" role="dialog" id="popup-3" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header"></div>
            <div class="modal-body pumkt-3 text-center">
                <div class="pumkt-3__description fz17 fw600">
                    Wow, looks like there're a lot of good reviews on <br>
                    your products have been hiden, only 5 can show.  <br>
                    <span class="fz18 fw700">BUY US A PIZZA</span> to show all of your reviews <br>
                    and <span class="fz18 fw700">INCREASE</span> your sales up to <span class="fz18 fw700">20%</span>! <br>
                </div>

                <div class="pumkt-3__yes">
                    <form method="post" action="{{ route('apps.upgradeHandle') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="plan" value="premium">
                        <button type="submit" class="fz17 fw700">Sounds great! Ordering pizza now</button>
                    </form>
                </div>

                <div class="pumkt-3__buttn-no">
                    <a href="#" data-dismiss="modal" class="ani-space-1px"> No, not now</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: POP-UP #3 -->
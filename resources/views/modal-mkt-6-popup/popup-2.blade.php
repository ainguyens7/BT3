<link href="https://fonts.googleapis.com/css?family=Kalam:700|Poppins:500,600,700" rel="stylesheet">
<!-- POP-UP #2 -->
<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#popup-2">Popup 2</button> -->

<div class="modal fade spopup-mkt spopup-mkt" tabindex="-1" role="dialog" id="mkt-popup-2" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content spopup-mkt__style-2">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body ff-poppins pumkt-2 text-center">
                <div class="fw600 pumkt-2__box-title fz18">
                    <span class="step-1">TIRED OF MANUALLY IMPORTING REVIEWS FOR EACH PRODUCT?</span>
                    <span class="step-2">SMART CHOISE!</span>
                    <span class="step-3">STILL NOT GOOD ENOUGH?</span>
                </div>

                <p class="pumkt-2__description fz18 step-1" id="mkt-popup-2__description">
                    Oberlo intergration function just got updated. <br>
                    It now can <b class="fw700 fz24">GET 1000+ REVIEWS</b> for multiple products <br>
                    with <b class="fw600 fz24">JUST 1 CLICK</b>. Wanna use it? 
                </p>
                <p class="pumkt-2__description fz18 step-2" id="mkt-popup-2__description">
                    Let's buy us a beer and enjoy the best of <br class="hidden-md hidden-lg hidden-sm"> <b class="fw600 fz24">ALI REVIEWS</b>
                </p>
                <p class="pumkt-2__description fz18 step-3" id="mkt-popup-2__description" style="letter-spacing: .5px;">
                    Well, maybe you need a boost with our <br>
                    <b class="fw600 fz24">10% LIFETIME DISCOUNT</b>. Hurry up, it's only <br>
                    available for 24h!
                </p>

                <div class="pumkt-2__buttn-yes">
                    <a href="#" id="mkt-popup-2__yes-1"  class="pu1-arrow ani-space-1px btnKalam step-1">
                        Yes, of course
                    </a>

                    <form method="post" action="{{ route('apps.upgradeHandle') }}" id="mkt-popup-2__yes-2" style="display: none;" class="step-2">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="plan" value="unlimited">
                        <button type="submit" class="pu1-arrow ani-space-1px btnKalam">
                            Buy a beer 
                        </button>
                    </form>
                </div>

                <div class="pumkt-2__buttn-no fw500">
                    <a href="#" class="ani-space-1px step-1" id="mkt-popup-2__no-1" data-dismiss="modal"> No, not now</a>
                    <a href="#" class="ani-space-1px step-2" id="mkt-popup-2__no-2" style="display: none;">I'll have to think about that</a>
                    {{--<a href="{{ route('apps.upgrade') }}" class="ani-space-1px btnKalam step-3" id="mkt-popup-2__discount" style="display: none; margin-bottom: 20px;font-size: 22px !important">Get my discount now and go Unlimited</a>--}}
                    <form method="post" id="mkt-popup-2__discount">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="app_plan" value="unlimited">
                        <input type="hidden" name="discount_code" value="INFINITY">
                        <button type="submit" class="ani-space-1px btnKalam step-3" style="display: none; margin-bottom: 20px;font-size: 22px !important">
                            Get my discount now and go Unlimited
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: POP-UP #2 -->
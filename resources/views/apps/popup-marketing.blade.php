@extends('layout.backend',['page_title' => 'Installation'])

@section('header')
    @include('layout.header', ['page_title' => 'Installation'])
@endsection

@section('container_content')
    <div class="home-wrap">
        <div class="home-container">
            <link href="https://fonts.googleapis.com/css?family=Montserrat:600|Poppins:400,500,500i,600,700" rel="stylesheet">
            <!-- POP-UP #1 -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#popup-1">Popup 1</button>

            <div class="modal fade spopup-mkt spopup-mkt__style-1" tabindex="-1" role="dialog" id="popup-1">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body ff-poppins">
                            <p class="fz26 pumkt-description">
                                <span class="fw700">DOING DROPSHIPPING?</span><br>
                                <span class="fw400 fz23">Let's hide AliReviews logo and get tons more features with</span><br>
                                <span class="fw700">JUST 3 CUPS OF COFFEE</span>
                            </p>
                            <img src="{{ url('images/backend/popup-mkt/pu1-arrow.png') }}" class="pu1-arrow bounceInDown animated">
                            <a href="#" class="fw600 fz18 ani-space-1px">Good for me, let's get some coffee!</a>
                            <p class="fw500i fz16 pumkt-sub-link">I'm ok with that logo</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: POP-UP #1 -->

            <!-- POP-UP #2 -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#popup-2">Popup 2</button>

            <div class="modal fade spopup-mkt spopup-mkt" tabindex="-1" role="dialog" id="popup-2">
                <div class="modal-dialog" role="document">
                    <div class="modal-content spopup-mkt__style-2">
                        <div class="modal-header"></div>
                        <div class="modal-body ff-poppins pumkt-2 text-center">
                            <div class="fw600 pumkt-2__box-title fz20">
                                TIRED OF MANUALLY IMPORTING REVIEWS FOR EACH PRODUCT?
                            </div>

                            <p class="pumkt-2__description fz16">
                                Oberlo intergration function just got updated. <br>
                                It now can <b class="fw600 fz24">GET 1000+ REVIEWS</b> for multiple products <br>
                                with <b class="fw600 fz24">JUST 1 CLICK</b>. Wanna use it? 
                            </p>

                            <div class="pumkt-2__buttn-yes">
                                <a href="#">
                                    <img src="{{ url('images/backend/popup-mkt/pu2-btn-yes.png') }}" class="pu1-arrow bounceInDown animated">
                                </a>
                            </div>

                            <div class="pumkt-2__buttn-no">
                                <a href="#" data-dismiss="modal" class="ani-space-1px"> No, not now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: POP-UP #2 -->

            <!-- POP-UP #3 -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#popup-3">Popup 3</button>

            <div class="modal fade spopup-mkt spopup-mkt__style-3" tabindex="-1" role="dialog" id="popup-3">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"></div>
                        <div class="modal-body ff-poppins pumkt-3 text-center">
                            <div class="pumkt-3__description fz16 fw500">
                                Wow, looks like there're a lot of good reviews on 
                                your products have been hiden, only 5 can show.  <br>
                                <span class="fz18">BUY US A PIZZA</span> to show all of your reviews 
                                and <span class="fz18">INCREASE</span> your sales up to <span class="fz18 fw700">20%</span>! <br>
                            </div>

                            <div class="pumkt-3__yes">
                                <a href="#" class="fz17">Sounds great! Ordering pizza now</a>
                            </div>

                            <div class="pumkt-3__buttn-no">
                                <a href="#" data-dismiss="modal" class="fw600 ani-space-1px"> No, not now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: POP-UP #3 -->

            <!-- POP-UP #4 -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#popup-4">Popup 4</button>

            <div class="modal fade spopup-mkt spopup-mkt__style-4" tabindex="-1" role="dialog" id="popup-4">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body ff-poppins pumkt-4 text-center">
                            <div class="pumkt-4__description fz20">
                                STILL HAVE SOME MORE 
                                PRODUCTS TO GET REVIEWS?<br>
                                <span class="fw600">DON'T WORRY</span>, HOW ABOUT WE 
                                GIVE YOU UNLIMITED PRODUCTS <br>
                                <span class="fw600">FOR JUST 3 BEERS?</span>
                            </div>

                            <div class="pumkt-4__yes">
                                <a href="#" class="fz17">Good for me, let's get some beers!</a>
                            </div>

                            <div class="pumkt-4__buttn-no">
                                <a href="#" class="ani-space-1px"> No, 10 is enough for now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: POP-UP #4 -->

            <!-- POP-UP #5 -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#popup-5">Popup 5</button>

            <div class="modal fade spopup-mkt spopup-mkt__style-5" tabindex="-1" role="dialog" id="popup-5">
                <div class="modal-dialog" role="document">
                    <div class="modal-content text-left">
                        <div class="modal-header"></div>
                        <div class="modal-body ff-poppins pumkt-5 fw500">
                            <div class="clearfix"></div>
                            <div class="pumkt-5__description fz26">
                                WANT A <span class="fw700">TIP</span> TO  <br>
                                <span class="fw700">INCREASE</span> YOUR <span class="fw700">SALES</span>? 
                            </div>

                            <div class="pumkt-5__yes">
                                <a href="#" class="fz14 fw600">Sure, 'cause it's free!</a>
                            </div>

                            <div class="pumkt-5__buttn-no">
                                <a href="#" class="ani-space-1px fz14 fw500" data-dismiss="modal"> No thanks, I'm good</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: POP-UP #5 -->

            <!-- POP-UP #6 -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#popup-6">Popup 6</button>

            <div class="modal fade spopup-mkt spopup-mkt__style-6" tabindex="-1" role="dialog" id="popup-6">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"></div>
                        <div class="modal-body ff-poppins pumkt-6 fw500">
                            <div class="clearfix"></div>
                            <div class="pumkt-6__description fz26 text-center">
                                WANT A <span class="fw700">TIP</span> TO  <br>
                                <span class="fw700">INCREASE</span> YOUR <span class="fw700">SALES</span>? 
                            </div>

                            <div class="pumkt-6__action text-center">
                                <a href="#" class="fw500 fz18 pumkt-6__action__yes">Yay, free tip</a>
                                <a href="#" class="fw500 fz18 pumkt-6__action__no" data-dismiss="modal">No thanks, I'm ok</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: POP-UP #6 -->
        </div>
    </div>
@endsection
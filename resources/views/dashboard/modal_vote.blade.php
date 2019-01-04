<!-- Delete Reviews Modal-->
<div id="voteForUsModal" class="modal fade ali-modal ali-modal--noline" role="dialog">
    <div class="modal-dialog modal-md" style="max-width: 600px;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body pad-0 text-center">
                <form id="formVoteForUs">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="material-icons icon fz18" aria-hidden="true">clear</i>
                        </button>
                    </div>
            
                    <div class="vote-for-us-wrap text-center">
                        <img src="{{ cdn('images/modals/great-app.png') }}" class="mar-b-15">
                        <h4>Thank you for being our valued customers!</h4>
            
                        {{-- STEP 1 --}}
                        <div class="step-1">
                            <p>
                                If you don't mind, please take a moment to let us know how you feel about this app.<br> 
                                We always want to improve to make sure every dollar you pay is well worth.
                            </p>
            
                            <div class="vote-for-us-btn-wrap mar-b-20">
                                <button type="button" class="ars-btn-o btn-bad-review button button--default mar-r-15">
                                    <i class="material-icons">sentiment_very_dissatisfied</i> <br>
                                    <span>Not good enough! <br> I have some feedback</span>
                                </button>

                                <a class="ars-btn button button--primary mar-l-15 btn-good-review">
                                    <i class="material-icons">sentiment_satisfied_alt</i> <br>
                                    <span>Great App! <br> I'll leave a good review</span>
                                </a>
                            </div>
            
                            <a href="javascript:void(0)" data-dismiss="modal" class="fz11">I already leave feedback</a>
                        </div>
            
                        {{-- STEP 2 --}}
                        <div class="step-2" style="display: none">
                            <p>We're really sorry for any inconvenience that may cause to you. <br>Let us know more about your problem?</p>
                            <textarea class="form-control mar-b-5" name="feedback" rows="5" placeholder="Not good enough, I have some feedback"></textarea>
                            <div>
                                <button type="submit" class="button button--primary ars-btn fw600 w-130px mar-t-10">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" value="{{ csrf_token() }}" name="_token">
                </form>
            </div>
        </div>
    </div>
</div>
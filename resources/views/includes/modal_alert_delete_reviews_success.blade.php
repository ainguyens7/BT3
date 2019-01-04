<!-- Delete Reviews Modal-->
<div id="alertDeleteReviewsSuccessModal" class="modal fade ali-modal ali-modal--noline" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content text-center">
            <div class="modal-body pad-0">
                <div class="add-review-finish">
                    <div class="text-center">
                        <div class="modal-body__logo mar-b-30">
                            <img src="{{ asset('images/modals/check.png') }}">
                        </div>
                        <div class="modal-body__content fz13">
                            <p class="note fw600 mar-b-35">{{ __('reviews.deleteSuccessAllReviewsInShop') }}</p>
                        </div>
                    </div>

                    <div class="text-center">
                        {{--<a class="button button--primary mar-r-5" onclick="">Finish</a>--}}

                        <button type="button" class="button button--primary" data-dismiss="modal">Finish</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
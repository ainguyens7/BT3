<!-- convertingUser Reviews Modal-->
<div id="convertingUser" class="modal fade ali-modal ali-modal--noline ali--transparent" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content text-center">
            <form class="text-center gtag_converting_user_form" method="post" action="{{ route('apps.upgradeHandle') }}">
                <input type="hidden" name="plan" value="premium">
                <img src="{{ asset('images/mkt/v4-cover/toggle-popup.png') }}">
                <button type="submit" class="gtag_converting_user_submit">
                    <img src="{{ asset('images/mkt/v4-cover/toggle-button.png') }}">
                </button>

                <p class="btn-no-thank">
                    <a href="javascript:void(0)" data-dismiss="modal" >No, thanks</a>
                </p>
                
                <input type="hidden" value="{{ csrf_token() }}" name="_token">
            </form>
        </div>
    </div>
</div>
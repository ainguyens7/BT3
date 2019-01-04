<!-- Delete Reviews Modal-->
<div id="inviteFriendModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="demo-icon icon-cancel-4"></i>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="ctn-invite-friend">
                    <img src="{{ URL::asset('images/backend/alireview-icon-extension.png') }}" alt="Logo AliReviews">

                    <h3>Thank you for your sharing</h3>
                    <p><b>Your sharing code is</b></p>

                    <input type="text" readonly value="{{ $code_invite }}" class="form-control">

                    <p><b>Total store you invited: {{ !empty($total_invited) ? $total_invited : 0 }}</b></p>

                    @if(!empty($listInvited))
                        @foreach($listInvited as $item)
                            <div class="row">
                                <div class="col-xs-7 text-left">{{ $item->shop_name }}</div>
                                <div class="col-xs-5">
                                    @if($item->status ==1)
                                        <span class="ars-btn" >Success</span>
                                    @else
                                        <span class="ars-btn ars-btn-error" >Fail</span>
                                    @endIf
                                </div>
                            </div>
                        @endForeach
                    @endIf
                </div>
            </div>
        </div>
    </div>
</div>
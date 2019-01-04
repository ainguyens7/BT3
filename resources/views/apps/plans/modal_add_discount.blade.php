<!-- Delete Reviews Modal-->
<div id="modalDiscountHandle" class="modal fade ali-modal ali-modal--noline" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <i class="material-icons">close</i>
                </button>
            </div>
            <div class="modal-body text-center">
                <form id="formDiscountHandle" method="get" onkeypress="return event.keyCode != 13;">
                    <img src="{{ cdn('images/icons/package-icon.png') }}" alt="Logo AliReviews">
                    <h3 class="fz13 mar-b-10 mar-t-0 text-uppercase">Package</h3>
                    <h2 class="fz27 fw700 mar-t-0 mar-b-15"><span class="package_name"></span></h2>
                    <p>Price : <b><span class="price"></span>$/month</b></p>
                    <p>Current discount: <b><span class="current_discount"></span>%</b></p>
                    <p>Total payment: <b><span class="total_payment"></span>$/month</b></p>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12 ">
                                <label for="discount_code">Your discount code:</label>
                                <input type="text" name="discount_code" placeholder="Input your code" id="discount_code" class="form-control text-center" autocomplete="off" autocorrect="off">
                            </div>
                            <div class="col-sm-12 text-center" style="margin-top: 5px">
                                <p class="message-code-discount style-error"></p>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="button button--primary ars-btn accept-voucher">ACCEPT</button>
                    <input type="hidden" value="" name="app_plan" class="form-control">
                    @if (isset($trial_day))
                        <input type="hidden" value="{{ $trial_day }}" name="trial_day">
                    @endif
                    <input type="hidden" value="{{ csrf_token() }}" name="_token">
                </form>
            </div>
        </div>
    </div>
</div>
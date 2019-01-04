{{-- send mail success right - to - left --}}
<div class="spopup-mkt  spopup-mkt__style-mail_success_1 spopup-mkt--right right-to-left">
    <div class="modal-dialog" role="document" style="width: auto;">
        <div class="modal-content text-left">
            <div class="modal-header">
                <button type="button" class="close" id="btnCloseSSMail" styleID="mail_success_1" style="position: absolute;top: 0;right: 10px;z-index: 9;font-size:45px;"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" style="background-color: transparent;box-shadow: none;padding:0;">
                <img src="{{ asset('images/backend/popup-mkt/5-Popup-Email-Noti_.png') }}" style="max-width: 100%;">
            </div>
        </div>
    </div>
</div>
{{-- send mail success right - to - left --}}

<script>
document.getElementById('btnCloseSSMail').addEventListener("click", function() {
    document.querySelector(".spopup-mkt__style-mail_success_1").classList.remove("inP");
});
</script>
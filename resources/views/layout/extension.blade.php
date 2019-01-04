@php
    $browser = \App\Helpers\Helpers::getBrowser();
@endphp
@if($browser['code'] == 'Chrome')
    <div class="modal ali-modal ali-modal--noline fade" id="modalInstallChromeExtension" tabindex="-1" role="dialog" aria-labelledby="modalInstallChromeExtension">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="width: 460px">
                <div class="modal-body text-center pad-0">
                    <div class="modal-body__logo mar-b-15">
                        <img src="{{ asset('images/modals/alireviews-install.png') }}" width="150">
                    </div>

                    <div class="modal-body__content fw-bold">
                        <p class="note fw600 mar-b-25 fz13">To immediately get
                            <span class="color-pink">reviews from AliExpress</span>, please use Google Chrome and install
                            <span class="color-pink">Ali Reviews Extension</span> on Chrome.
                        </p>
                    </div>
                </div>

                <div class="modal-footer text-center">
                    {{--<button type="button" class="button button--primary w-130px extension-install-btn mar-r-5">Install now</button>--}}
                    <a href="https://chrome.google.com/webstore/detail/ali-reviews-aliexpress-re/bbaogjaeflnjolejjcpceoapngapnbaj" target="_blank" type="button" class="button button--primary w-130px  mar-r-5">Install now</a>
                    <button type="button" class="button button--default w-130px mar-l-5" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
@endIf

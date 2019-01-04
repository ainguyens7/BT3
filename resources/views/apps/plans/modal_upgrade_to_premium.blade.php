<!-- Upgrade To Premium Modal-->
<div id="modalUpgradeToPremium" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg upgrade-premium">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="upgrade-premium-wrap">
                    <div class="upgrade-premium-wrap__body">
                        <h1 class="upgrade-premium-wrap--title fz48"><b>"SAVE 15% LIFETIME OFF TO UPGRADE TO PREMIUM PLAN"</b></h1>
                        <h2 class="upgrade-premium-wrap--sub-title fz36">HURRY! 24 HOURS ONLY</h2>

                        <div class="jquery-countdown">
                            <div class="your-clock"></div>
                        </div>

                        <div class="wrap-sbm">
                            <a href="{{route('apps.upgrade')}}" id="hidePopupPromotion" class="button button--default sbm">
                                <b>GET CODE: <span class="fz48">FAST15</span></b>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts-modal')
<!-- FlipClock -->
<script src="{{ asset('libs/flipclock/flipclock.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('libs/flipclock/flipclock.css') }}">

<!-- END:FlipClock -->
@endpush
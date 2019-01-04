@if (isset($shopPlanInfo))
    @if($shopPlanInfo['planInfo']['name'] =='free')
        <button type="submit" class="button button-pro">UPGRADE</button>
        <div>
            <a class="btn-add-discount-code have-voucher-btn" data-plan="premium" href="javascript:void(0)" id="pro-upgrade-voucher">I Have Voucher</a>
        </div>
    @else
        @if($shopPlanInfo['planInfo']['name'] =='premium')
            <a href="{{ route('products.list') }}" class="button active">YOU ARE HERE</a>
        @else
            <a class="button btn-remove-data" data-plan='premium' href="javascript:void(0)">DOWNGRADE</a>
        @endif
        @if($shopPlanInfo['planInfo']['name'] =='free')
            <div>
                <a class="btn-add-discount-code have-voucher-btn" data-plan="premium" href="javascript:void(0)" id="pro-upgrade-voucher">I Have Voucher</a>
            </div>
        @endif
    @endIf
@endif
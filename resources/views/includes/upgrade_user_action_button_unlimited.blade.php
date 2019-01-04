@if (isset($shopPlanInfo))
    @if(!empty($shopPlanInfo['planInfo']) &&  $shopPlanInfo['planInfo']['name'] =='unlimited')
        <a href="{{ route('products.list') }}" class="button active">YOU ARE HERE</a>
    @else
        <button type="submit" class="button button-unlimited">UPGRADE</button>
        <div>
            <a class="btn-add-discount-code have-voucher-btn" data-plan="unlimited" href="javascript:void(0)" id="unlimited-upgrade-voucher">I Have Voucher</a>
        </div>
    @endIf
@endif
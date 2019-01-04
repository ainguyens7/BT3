@if (isset($shopPlanInfo))
    @if ($shopPlanInfo['planInfo']['name'] === 'free' )
        <a class="button active" href="{{ route('products.list') }}">YOU ARE HERE</a>
    @else
        <a class="button btn-remove-data" data-plan='free' href="javascript:void(0)">DOWNGRADE</a>
    @endif
@endif
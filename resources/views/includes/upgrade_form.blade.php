<form method="post" action="{{ route('apps.upgradeHandle') }}">
    {{ csrf_field() }}
    <input type="hidden" name="plan" value=" {{ $plan }}">
    @if ($plan === config('plans.premium.name'))
        @include('includes/upgrade_user_action_button_premium', ['shopPlanInfo' => $shopPlanInfo])
    @elseif ($plan === config('plans.unlimited.name'))
        @include('includes/upgrade_user_action_button_unlimited', ['shopPlanInfo' => $shopPlanInfo])
    @endif
</form>
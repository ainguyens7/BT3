@extends('layout.dashboard',['page_title' => 'Decline Charged'])

@section('body_content')
<div class="decline-page">
    <div class="blind-decline"></div>

    <form>
        {{-- <a href="{{ asset('') }}" class="close">
            <i class="material-icons">close</i>
        </a> --}}
        <img src="{{ asset('images/icons/resintall-icon.png') }}">
        <p class="decline__title">We were unable to process your payment.</p>
        <p class="decline__subtitle">Please check your information and try again</p>
        <a href="{{ route('apps.dashboard') }}" class="button button--default mar-r-5">Cancel</a>
        <a href="{{ route('apps.addCharged',['app_plan' => $plan]) }}" class="button button--primary mar-l-5">Retry</a>
        <p class="decline_note fz11 lh18">If you have questions about payment, please contact our support, or visit Shopify Payment Term.</p>
    </form>
</div>
@endsection

@section('body_script')
<script>
    document.body.classList.add('modal-open');
</script>
@endsection
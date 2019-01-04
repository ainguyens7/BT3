@extends('layout.dashboard',['page_title' => 'Thank You!'])

@section('head_script')
    @include('third-party/fb_pixel')
    @include('third-party/gtag_install_successful')
    @include('third-party/adroll')
@endSection

@section('breadcrumb')
    @include('partials.breadcrumb', ['page_title' => 'Thank You!'])
@endsection

@section('body_content')
<div class="wrapper-space wrapper-space--45 bg-white thank-page">
    <div class="clearfix"></div>
    <div class="thank-you-wrap">
        <script>
            fbq('track', 'Purchase');
        </script>
        <div class="wrap-thank-logo">
            <img src="{{ asset('images/logo.png') }}" alt="AliReviews">
           <div>
                <h2>Thank You!</h2>
                <p>Let’s get sales up together</p>
                <a href="{{ route('products.list') }}" class="button button--primary discover-charge-thanks">DISCOVER NOW</a>
           </div>
        </div>
    </div>
</div>
@endsection
@extends('layout.dashboard',['page_title' => 'Installation Successful'])

@section('head_script')
    @include('third-party/fb_pixel')
    @include('third-party/gtag_install_successful')
@endSection

@section('body_content')
    <div class="thank-you-wrap">
        <img src="{{ asset('') }}" alt="AliReviews">
        <h2>Thank You</h2>
        <p>Let's Get Sales Up Together</p>
        <a href="{{ route('products.list') }}" class="discover-charge-thanks">DISCOVER YOUR ALIREVIEWS</a>
    </div>
@endsection
@include('partials/meta_link')
@yield('styles')
@if(env('APP_ENV') == 'production' && empty($non_google_analytics))
    @include('third-party/gtag')
@endif
@include('third-party/gtag_manager_head')
<!-- Intercom widget -->
@php
    $shopRepo = \App\Factory\RepositoryFactory::shopsReposity();
    $find = $shopRepo->detail(['shop_id' => session('shopId')]);
    $shopInfo = $find['status'] ? $find['shopInfo'] : NULL;
    $intercom = new \App\Services\IntercomService(session('shopId'));
@endphp

@if (isset($shopInfo))
    @php
        $hashUser = hash_hmac(
                   'sha256', // hash function
                        $shopInfo->shop_email,
                         'laP76F9AdHmESp6sfNmtgHsX0fqVMFrrEdgiNuMq'
                );
    @endphp
    <script>
        window.intercomSettings = {
            app_id: "pqt7dlbm",
            name: "{{$shopInfo->shop_owner}}",
            email: "{{$shopInfo->shop_email}}",
            user_hash: "{{$hashUser}}",
            created_at: "{{$shopInfo->created_at->timestamp}}",
            installed_ar: "{{$shopInfo->created_at->timestamp}}",
            uninstalled_ar: "{{ !empty($shopInfo->cancelled_on) ? strtotime($shopInfo->cancelled_on) : NULL }}",
            trial_start: null,
            plan_name: "{{$shopInfo->app_plan}}",
            plan_ar: "{{$shopInfo->app_plan}}",
            shop_domain: "{{$shopInfo->shop_name}}",
            app_name: "alireviews",
            ali_reviews: true,
            imported_reviews: "{{$intercom->getTotalReviews()}}",
            reviewed_products: "{{$intercom->getTotalReviewedProducts()}}"
        };
    </script>

    @include('third-party/intercom')
@endif

<script>
    var appUrl = "{{ url('') }}";
    var extensionIdDefine = "{{ env('EXTENSION_ID') }}";
</script>

<link rel="chrome-webstore-item" href="https://chrome.google.com/webstore/detail/bbaogjaeflnjolejjcpceoapngapnbaj">
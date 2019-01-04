@extends('layout.dashboard',['page_title' => 'Manage Reviews'])

@section('breadcrumb')
    @include('partials/breadcrumb', ['breadcrumb_link' => 'Manage reviews', 'router'=>'products.list'])
    @include('partials/breadcrumb', ['breadcrumb_link' => 'Product'])
@endsection

@section('body_content')
    <div class="wrapper-space product-reviews-page" id="page-list-product-get-review">
        <!-- Product action -->
        @include('partials/manage_review_product_heading')
        <!-- END: Product action -->

        <!-- Product summary -->
        @include('partials/manage_review_product_summary')
        <!-- END: Product summary -->

        <!-- Product action -->
        @include('partials/manage_review_product_action')
        <!-- END: Product action -->

        <!-- Review list -->
        @include('partials/manage_review_product_review_list')
        <!-- END: Review list -->
        
    </div>
    @include('reviewsBackend.modal_delete_review')
    @include('reviewsBackend.modal_edit_review')
    @include('reviewsBackend.modal_confirm_delete_multi')
    @include('reviewsBackend.modal_confirm_delete_all')
    @include('reviewsBackend.modal_confirm_publish_all')
    @include('reviewsBackend.modal_confirm_unpublish_all')
    @include('layout.extension')
    @include('reviewsBackend.modal_upgrade_pro')
@endsection
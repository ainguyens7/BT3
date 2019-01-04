@extends('layout.dashboard',['page_title' => 'Pending'])
@section('breadcrumb')
    @include('partials/breadcrumb', ['breadcrumb_link' => 'Manage reviews', 'router'=>'products.list'])
    @include('partials/breadcrumb', ['breadcrumb_link' => 'Pending reviews'])
@endsection

@section('body_content')
    <div class="wrapper-space pending-reviews-page">
        <!-- Review action  -->
        @include('partials/review_pending_action')
        <!-- END: Review action -->

        <!-- Review list -->
        @include('partials/pending_review_list')
        <!-- END: Review list -->

        <!-- Pagination -->
        <div class="text-right text-center-md" style="margin-top: -5px;">
            {{ $listReview->links('vendor.pagination.alireview') }}
        </div>
        <!-- END: Pagination -->
    </div>
     @include('reviewsBackend.modal_delete_review')
    @include('reviewsBackend.modal_confirm_delete_multi')
@endsection

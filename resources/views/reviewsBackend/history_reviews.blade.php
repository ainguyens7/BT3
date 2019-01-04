@extends('layout.dashboard',['page_title' => 'Approve'])
@section('breadcrumb')
    @include('partials/breadcrumb', ['breadcrumb_link' => 'Approve'])
@endsection

@section('body_content')
    <div class="wrapper-space">
        <!-- Review action  -->
        @include('partials/review_approve_action')
        <!-- END: Review action -->

        <!-- Review list -->
        @include('partials/approve_review_list')
        <!-- END: Review list -->

        <!-- Pagination -->
        <div class="text-right text-center-md" style="margin-top: -5px;">
            {{ $listReview->links('vendor.pagination.alireview') }}
        </div>
        <!-- END: Pagination -->
    </div>
@endsection

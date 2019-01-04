@extends('layout.dashboard',['page_title' => '500 Error'])

@section('breadcrumb')
    @include('partials/breadcrumb', ['page_title' => 'Error'])
@endsection


@section('body_content')
    <div class="clearfix"></div>
    <div class="wrapper-space wrapper-space--45 bg-white error-page">
        <div class="wrap-error-style">
            <img src="{{ asset('images/error-page-new.svg') }}" alt="Error" width="400">
            <h1>Error!</h1>
            <h4>Error ID: {{ $sentryID }}</h4>
            <span class="alrv-toggle-intercom" style="cursor: pointer !important;">For further support, please send this eventID to our CS team.</span>
        </div>
    </div>
@endSection

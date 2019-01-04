@extends('layout.dashboard',['page_title' => '404 Error'])

@section('breadcrumb')
    @include('partials/breadcrumb', ['page_title' => 'Error'])
@endsection


@section('body_content')
    <div class="clearfix"></div>
    <div class="wrapper-space wrapper-space--45 bg-white error-page">
        <div class="wrap-error-style">
            <img src="{{ asset('images/error-page-new.svg') }}" alt="Error" width="400">
            <h1>404 Error!</h1>
            <h4>Page not found</h4>
        </div>
    </div>
@endSection

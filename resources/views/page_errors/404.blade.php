@extends('layout.dashboard',['page_title' => 'Error'])

@section('body_content')
    <div class="clearfix"></div>
    <div class="wrapper-space wrapper-space--45 bg-white error-page">
        <div class="wrap-error-style">
            <img src="{{ asset('images/error-page-new.svg') }}" alt="Error" width="400">
            <a href="{{ route('apps.installApp') }}">
                <button class="button button--primary setting-submit-btn">Retry</button>
            </a>
        </div>
    </div>
@endSection
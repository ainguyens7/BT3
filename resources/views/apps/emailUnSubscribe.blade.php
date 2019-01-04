@extends('layout.backend',['page_title' => 'Email UnSubscribe'])

@section('header')
    @include('layout.header', ['page_title' => 'Email UnSubscribe'])
@endSection

@section('container_content')
    <div class="thank-you-wrap">
        <img src="{{ URL::asset('images/backend/alireview-icon-extension.png') }}" alt="Logo AliReviews">
    </div>
@endsection
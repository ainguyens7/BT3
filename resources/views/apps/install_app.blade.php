@extends('layout.dashboard',['page_title' => 'Installation'])

@section('body_header')
    @include('partials/body_header')
@endsection

@section('body_content')
    <div class="wrapper-space wrapper-space--45 bg-white welcome-page">
        <div class="clearfix"></div>
        <div class="wrapper-box_ mar-auto text-center">
            <img src="{{ cdn('images/welcome-page.png') }}" alt="" class="mar-b-40 wow fadeInUp">

            <form method="post" action="{{ route('apps.installHandle') }}" name="installShopify">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row">
                    <div class="col-md-8">
                        <input type="text" value="{{ !empty($_REQUEST['shop']) ? $_REQUEST['shop'] : '' }}" class="form-control wow fadeInUp" placeholder="Your link" aria-describedby="basic-addon2" name="shop" autofocus>
                    </div>

                    <div class="col-md-3">
                        <button type="button" class="button button--primary fw600 wow fadeInUp buttn__submit">Enter</button>
                    </div>

                    <div class="col-md-12">
                        <p class="style-error mar-t-15" id="shop-error">{{ !empty($message) ? $message : $errors->first('shop') }}</p>
                    </div>
                </div>
            </form>

            <div class="home-redirect-shoppify">
                <a href="https://www.shopify.com/pricing?ref=developer-4d9a2b35fefa4208&utm_campaign=webappdata" target="_blank" class="bg-pink">
                    Click here to create your own store if you haven’t used Shopify yet
                </a>
            </div>
        </div>
    </div>
@endsection
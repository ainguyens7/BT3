@extends('layout.dashboard',['page_title' => 'Get started'])
@section('breadcrumb')
    @include('partials/breadcrumb', ['breadcrumb_link' => 'Get started'])
@endsection
@section('body_content')
    <div class="clearfix"></div>
    <div class="wrapper-space wrapper-space--45 bg-white getting-started-page">
        <h2 class="mar-t-0 fz25 fw-bold color-dark-blue mar-b-15">{{__('settings.title_4')}}</h2>
        <p class="fz15 color-dark-blue mar-b-50">{{__('settings.description_getting_started')}}</p>

        <div class="row mar-b-60">
            <div class="col-md-6">
                <div class="wrapper-owl mar-md-auto">
                    <div class="owl-carousel owl-theme ali-owl ali-owl--style-1">
                        <div class="item">
                            <img src="{{ URL::asset('images/get-start-demo-step-1.png') }}"alt="">
                        </div>
                        <div class="item">
                            <img src="{{ URL::asset('images/get-start-demo-step-2.png') }}" alt="">
                        </div>
                        <div class="item">
                            <img src="{{ URL::asset('images/get-start-demo-step-3.png') }}" alt="">
                        </div>
                        <div class="item">
                            <img src="{{ URL::asset('images/get-start-demo-step-4.png') }}" alt="">
                        </div>
                        <div class="item">
                            <img src="{{ URL::asset('images/get-start-demo-step-5.png') }}" alt="">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 text-align-md-c">
                <h3 class="ali-owl--title fz25 fw-bold color-dark-blue mar-b-15 mar-t-20 text-uppercase">{{__('settings.title_41')}}</h3>
                <h4 class="ali-owl--sub-title color-pink mar-b-30 fw600">{{__('settings.title_42')}}</h4>
                <p class="ali-owl--description ali-body-large fz13">{!!  __('settings.title_install_now') !!}</p>
                <div class="ali-owl-btn mar-b-20"></div>
                <div class="color-pink" style="display: flex; align-items: center;">
                    <i class="material-icons mar-r-5 fz15">help</i>
                    <a href="#" class="color-pink fz13">{{__('settings.title_get_more_website')}}</a>
                </div>
            </div>
        </div>
    </div>
@endsection

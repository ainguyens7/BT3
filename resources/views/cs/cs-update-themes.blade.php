@extends('layout.dashboard',['page_title' => 'Update Themes'])

@section('breadcrumb')
    @include('partials/breadcrumb', ['breadcrumb_link' => 'Update Themes'])
@endsection
@section('body_content')
    <div class="wrapper-space wrapper-space--45 bg-white">
        <div class="general-setting-wrap">
            <div class="text-center">
                <img src="{{ cdn('images/icons/download-icon.png') }}" alt="">
                <div class="display-message"  style="text-align: left">
                    @if( Session::has( 'success' ))
                        <div class="alert alert-success">
                             {!! Session::get( 'success' ) !!}
                        </div>
                    @elseif( Session::has( 'warning' ))
                        <div class="alert alert-warning">
                            {!!__('cs.cs_update_themes_error')!!}
                        </div>
                    @endif
                </div>
                @if(!empty($themeName))
                    <form id="form_update_theme" method="post" action="{{route('cs.updateThemeCS')}}" >
                        <div class="clearfix fz21" style="margin: 20px 0 0 0;">
                            <p style="line-height: 1.8;" class="fz15 mar-b-0">
                                <strong>Update Ali Reviews theme</strong><br>
                                
                            <br>
                            Please click the button below to continue
                            </p>
                        </div>
                        <button type="submit" class="button button--primary setting-submit-btn mar-t-30">{{__('cs.button_update_theme')}}</button>
                        {!! csrf_field() !!}
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
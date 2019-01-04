@extends('layout.dashboard',['page_title' => 'Update App'])
@section('breadcrumb')
    @include('partials/breadcrumb', ['breadcrumb_link' => 'Update Master'])
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
                <form id="form_update_master" method="post" action="{{route('cs.updateMasterCS')}}" >
                    <div class="clearfix fz21" style="margin: 15px 0 0 0;">
                        <p style="line-height: 1.8;" class="fz15 mar-b-0">
                            <strong>Becareful with this update</strong><br>
                            All jobs will be re run, it take an hour to run<br>
                            if shop are many products.<br>
                            Jobs list: <br>
                        <ul style="list-style: none; text-align: left">
                            <li><a>-</a> Init Database</li>
                            <li><a>-</a> Import product</li>
                            <li><a>-</a> Add Code AliReviews To Theme Apps if not exists</li>
                            <li><a>-</a> Add template load asset if not exists</li>
                            <li><a>-</a> Update database if not exists ( column reivew_link, column pin )</li>
                            <li><a>-</a> Add review box if not exists</li>
                            <li><a>-</a> Add review badge if not exists</li>
                            <li><a>-</a> Add google rating if not exists</li>
                        </ul>
                        <br>
                        Please click the button below to continue
                        </p>
                    </div>
                    <button type="submit" class="button button--primary setting-submit-btn mar-t-30">{{__('cs.button_update_master')}}</button>
                    {!! csrf_field() !!}
                </form>
            </div>
        </div>
    </div>
@endsection
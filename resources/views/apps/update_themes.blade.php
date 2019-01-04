@extends('layout.backend',['page_title' => 'Update Code In The Theme'])

@section('header')
    @include('layout.header', ['page_title' => 'Update Code In The Theme'])
@endsection

@section('container_content')

    <div class="general-setting-wrap">
        <div class="text-center">
            <img src="{{ url('images/backend/alireview-icon-extension.png') }}" alt="">
            <form method="post" action="" id="form-update-themes">
                {{--<div class="clearfix" style="margin: 20px 0 0 0;font-size: 20px;">--}}
                    {{--<p>--}}
                    {{--</p>--}}
                {{--</div>--}}
                <button type="button" class="setting-submit-btn">Update</button>
            </form>
        </div>
    </div>
@endsection
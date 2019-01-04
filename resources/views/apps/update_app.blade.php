@extends('layout.dashboard',['page_title' => 'Update App'])

@section('body_content')
    <div class="wrapper-space wrapper-space--45 bg-white">
        <div class="general-setting-wrap">
            <div class="text-center">
                <img src="{{ cdn('images/icons/download-icon.png') }}" alt="">
                <form method="post" action="" id="form-update-app">
                    <div class="clearfix fz21" style="margin: 15px 0 0 0;">
                        <p style="line-height: 1.8;" class="fz15 mar-b-0">
                            The new features are available. <br>
                            Please click the button below to continue
                        </p>

                        <a href="https://fireapps.io/ali-reviews-changelog/" target="_blank" class="fz13">Read more <span class="icon-forward"></span> </a>
                    </div>
                    <button type="button" class="button button--primary setting-submit-btn mar-t-30">Update Now</button>
                </form>
            </div>
        </div>
    </div>
@endsection
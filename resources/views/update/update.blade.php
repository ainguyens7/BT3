@extends('layout.dashboard',['page_title' => 'Internal Update App'])

@section('`body_content')

    <div class="general-setting-wrap">
        <div class="text-center">
            <img src="{{ cdn('images/backend/alireview-icon-extension.png') }}" alt="">
            <form method="post" action="" id="form-internal-update">
                <div class="clearfix" style="margin: 20px 0 0 0;font-size: 20px;">
                    <p>
                        The new features are available.
                        <br /><br />
                        Please click the below button to update those features.
                    </p>

                    <a href="https://fireapps.io/changelog/" target="_blank">Read more here <span class="icon-forward"></span> </a>
                </div>
                <button type="button" class="setting-submit-btn">Update App</button>
            </form>
        </div>
    </div>
@endsection
@extends('layout.dashboard',['page_title' => 'Update App'])

@section('body_content')
    <div class="wrapper-space wrapper-space--45 bg-white">
        <div class="general-setting-wrap">
            <div class="text-center">
                <img src="{{ cdn('images/icons/download-icon.png') }}" alt="">
                <form method="post" action="{{route('critical.update.handle') }}" id="form-critical-update-app">
                    <div class="clearfix fz21" style="margin: 15px 0 0 0;">
                        <p style="line-height: 1.8;" class="fz15 mar-b-0">
                            <strong>Becareful with this update</strong><br>
                            All jobs will be re run, it take an hour to run<br>
                            if shop are many products.<br>
                            Jobs list: <br>
                            <ul style="list-style: none; text-align: left">
                                <li>Init database</li>
                                <li>Import product (heavy job)</li>
                                <li>Add metafield</li>
                                <li>Add asset file (css/js) to shopify</li>
                                <li>Register webhook</li>
                                <li>Add review box</li>
                                <li>Add placeholder metafield</li>
                            </ul>
                            <br>
                            Please click the button below to continue
                        </p>
                    </div>
                    <button type="button" class="button button--primary setting-submit-btn mar-t-30">Dont click button</button>
                </form>
            </div>
        </div>
    </div>
@endsection
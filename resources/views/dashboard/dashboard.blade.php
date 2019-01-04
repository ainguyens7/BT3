@extends('layout.dashboard',['page_title' => 'Dashboard'])
@section('body_content')
    <div class="clearfix"></div>
    <div class="wrapper-space dashboard-page">
        <div class="row flex-equal">
            <div class="col-md-4">
                <!-- Media Object Point -->
                <div class="media media--point dashboard-list-item">
                    <div class="dashboard-list-item--wrap bg-white">
                        <div class="media-left media-middle media-top">
                            <span class="fz35 fw600 no-wrap dashboard-list-item__number">+ {{  number_format($reviewWaiting)}}</span>
                        </div>
                        <div class="media-body media-body--icon">
                            <h4 class="media-heading fz17 fw-bold color-dark-blue">
                                <a href="{{route("reviews.review_approve")}}">Pending Reviews</a>
                            </h4>
                            {{--<p class="fz13 mr0">new reviews / all</p>--}}
                        </div>
                    </div>
                </div>
                <!-- END: Media Object Point -->
            </div>
            <div class="col-md-4">
                <!-- Media Object Point -->
                <div class="media media--point dashboard-list-item">
                    <div class="dashboard-list-item--wrap bg-white">
                        <div class="media-left media-middle media-top">
                            <span class="fz35 fw600 no-wrap dashboard-list-item__number">+ {{number_format($productsReviews)}}</span>
                        </div>
                        <div class="media-body media-body--icon">
                            <h4 class="media-heading fz17 fw-bold color-dark-blue">
                                <a href="{{route("products.list").'?is_review=1&title='}}">Reviewed Products</a>
                            </h4>
                            {{--<p class="fz13 mr0">this month / all time</p>--}}
                        </div>
                    </div>
                </div>
                <!-- END: Media Object Point -->
            </div>
            <div class="col-md-4">
                <!-- Media Object Point -->
                <div class="media media--point dashboard-list-item">
                    <div class="dashboard-list-item--wrap bg-white">
                        <div class="media-left media-middle media-top">
                            <span class="fz35 fw600 no-wrap dashboard-list-item__number">+ {{ number_format($reviewImport) }}</span>
                        </div>

                        <div class="media-body media-body--icon">
                            <h4 class="media-heading fz17 fw-bold color-dark-blue">
                                <a href="{{route("products.list").'?is_review=1&title='}}">Imported Reviews</a>
                            </h4>
                            <p class="fz13 mr0">This month + {{ number_format($reviewImportThisMonth) }}</p>
                        </div>
                    </div>
                </div>
                <!-- END: Media Object Point -->
            </div>
        </div>

        {{-- Changelog + Youtube --}}
        <div class="row flex-equal">
            <div class="col-md-4">
                <!-- Media Object Point -->
                <div class="media media--point dashboard-list-item changelogs-item">
                    <div class="dashboard-list-item--wrap bg-white pad-t-30">
                        <div class="media-left media-top">
                            {{-- <i class="material-icons media-left__icon">group_add</i> --}}
                            <img src="{{ cdn('images/icons/changelog-icon.png') }}">
                        </div>
                        <div class="media-body media-body--icon fw500">
                            <h4 class="media-heading fz17 fw-bold color-dark-blue mar-b-15">Changelog {{ config('common.app_version') }} (Sep 6, 2018)</h4>
                            <p class="fz13 mar-b-10">- Reverse back to JS for more stable (remove metafields injection)</p>
                            <p class="fz13 mar-b-10">- Temporary remove in-line SEO & Google Rich snippet. Move Add Google Rating button into Product page</p>
                            <p class="see-more">
                                <a href="https://fireapps.io/ali-reviews-changelog/" target="_blank">See more</a>
                            </p>
                        </div>
                    </div>
                </div> 
            </div>

            <div class="col-md-8 ">
                <div class="list-ytb-wrap">
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="youtube-embed-list" id="result-vid-ytb">
                                <li class="active">
                                    <div class="videoWrapper">
                                        <div id="ytplayer_0"></div>
                                    </div>
                                </li>
                                <li>
                                    <div class="videoWrapper">
                                        <div id="ytplayer_1"></div>
                                    </div>
                                </li>
                                <li>
                                    <div class="videoWrapper">
                                        <div id="ytplayer_2"></div>
                                    </div>
                                </li>
                                <li>
                                    <div class="videoWrapper">
                                        <div id="ytplayer_3"></div>
                                    </div>
                                </li>
                                <li>
                                    <div class="videoWrapper">
                                        <div id="ytplayer_4"></div>
                                    </div>
                                </li>
                                <li>
                                    <div class="videoWrapper">
                                        <div id="ytplayer_5"></div>
                                    </div>
                                </li>
                                <li>
                                    <div class="videoWrapper">
                                        <div id="ytplayer_6"></div>
                                    </div>
                                </li>
                                <li>
                                    <div class="videoWrapper">
                                        <div id="ytplayer_7"></div>
                                    </div>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
    
                        <div class="col-md-6">
                            <div class="ytb-wrap-right">
                                <h4 class="media-heading fz18 fw-bold color-dark-blue mar-b-15">How to use</h4>
                                <div class="col-youtube-link">
                                    <div class="">
                                        <ul class="youtube-link-list">
                                            <li class="active" el="0"><i class="demo-icon icon-youtube"></i>How to import reviews from AliExpress</li>
                                            <li el="1"><i class="demo-icon icon-youtube"></i>How to set General Settings</li>
                                            <li el="2"><i class="demo-icon icon-youtube"></i>How to use Oberlo Integration function</li>
                                            <li el="3"><i class="demo-icon icon-youtube"></i>How to add Google rating to the Product page</li>
                                            <li el="4"><i class="demo-icon icon-youtube"></i>How to customize your store's style</li>
                                            <li el="5"><i class="demo-icon icon-youtube"></i>How to create a review page</li>
                                            <li el="6"><i class="demo-icon icon-youtube"></i>How to use translation function</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div> {{-- row --}}

        <div class="row flex-equal quickguide">
            <div class="col-md-12">
                <!-- Media Object Point -->
                <div class="media media--point dashboard-list-item changelogs-item">
                    <div class="dashboard-list-item--wrap bg-white pad-t-30">
                        <div class="media-left media-top">
                            {{-- <i class="material-icons media-left__icon">group_add</i> --}}
                            <img src="{{ cdn('images/icons/book-icon.png') }}">
                        </div>
                        <div class="media-body media-body--icon fw500">
                            <h3 class="mar-t-0 mar-b-20 fw700 fz17">{{ __('reviews.review_quick_guide') }}</h3>
                                <div class="mar-l-15">
                                <div class="row mar-b-30">
                                    <div class="col-lg-3 mar-b-30-lg quickguide_thumb text-align-lg-c pad-l-0-lg">
                                        <img src="{{ cdn('images/dashboard/quick_1.png') }}" alt="">
                                    </div>
                                        <div class="col-lg-8">
                                        <h4 class="media-heading fz15 fw600 color-dark-blue mar-b-10">1. {{ __('reviews.review_badge') }}</h4>
                                        <p class="mar-b-20 lh17">
                                            - Here’re details that guide you how to add the code snippet in order to display rating star in the product page
                                            <a href="https://help.fireapps.io/ali-reviews/storefront-display/how-can-i-show-rating-star-in-the-product-page" class="read-more" target="_blank">(read more)</a>
                                        </p>
                                        <div class="pre-code">{{ trim($reviewBadge) }}</div>
                                    </div>
                                </div>  {{-- row --}}
                                
                                <div class="row mar-b-30">
                                    <div class="col-lg-3 mar-b-30-lg quickguide_thumb text-align-lg-c pad-l-0-lg">
                                        <img src="{{ cdn('images/dashboard/quick_2.png') }}" alt="">
                                    </div>
                                        <div class="col-lg-8">
                                        <h4 class="media-heading fz15 fw600 color-dark-blue mar-b-10">2. {{ __('reviews.review_badge_collection') }}</h4>
                                        <p class="mar-b-20 lh17">
                                            - Here’re details that guide you how to display rating star on your collection page
                                            <a href="https://help.fireapps.io/ali-reviews/storefront-display/how-can-i-show-the-rating-star-in-the-collection-page" class="read-more" target="_blank">(read more)</a>
                                        </p>
                                        <div class="pre-code">{{ trim($reviewBadgeCollection) }}</div>
                                    </div>
                                </div> {{-- row --}}
                                    <div class="row mar-b-30">
                                    <div class="col-lg-3 mar-b-30-lg quickguide_thumb text-align-lg-c pad-l-0-lg">
                                        <img src="{{ cdn('images/dashboard/quick_3.png') }}" alt="">
                                    </div>
                                        <div class="col-lg-8">
                                        <h4 class="media-heading fz15 fw600 color-dark-blue mar-b-10">3. {{ __('reviews.review_box') }}</h4>
                                        <p class="mar-b-20 lh17">
                                            - Here’re details that guide you how to display reviews box on your product page
                                            <a href="https://help.fireapps.io/ali-reviews/storefront-display/how-to-add-review-box-to-product-page" class="read-more" target="_blank">(read more)</a>
                                        </p>
                                        <div class="pre-code mar-t-10">{{ trim($reviewBox_2) }}</div>
                                    </div>
                                </div> {{-- row --}}
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div> {{-- row --}}
    </div>
@endsection

@section('body_script')
    <script>
        $('body').removeClass('step-highlight-open').removeClass('step-highlight-policy');
        (function($) {
            $('.youtube-link-list li').on('click', function() {
                $('.youtube-link-list li').removeClass('active');
                $(this).addClass('active');
                var _index = $(this).index();
                $('.youtube-embed-list li').removeClass('active');
                $('.youtube-embed-list li').eq(_index).addClass('active');
            });
        })(jQuery);
        // Load the IFrame Player API code asynchronously.
        var tag = document.createElement('script');
        tag.src = "https://www.youtube.com/player_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        // Step 1: create variable new player_X, ul.youtube-embed-list: create new element ytplayer_X
        var player_0;
        var player_1;
        var player_2;
        var player_3;
        var player_4;
        var player_5;
        var player_6;
        var player_7;

        // Step 2: create YoutubeIframe(ytplayer_X, ID-Video-ytb)
        function onYouTubeIframeAPIReady() {
            // player_0 = YoutubeIframe('ytplayer_0', 'WAeGe9wyZiw');
            // player_1 = YoutubeIframe('ytplayer_1', 'iwPa-OiOHS4');
            // player_2 = YoutubeIframe('ytplayer_2', 'q6Bhls3Z4Fk');
            // player_4 = YoutubeIframe('ytplayer_4', 'G-hLy7Pc-vc');
            // player_5 = YoutubeIframe('ytplayer_5', 'F2v-feCGEv4');

            player_0 = YoutubeIframe('ytplayer_0', 'Bpd8k7mEvgc');
            player_1 = YoutubeIframe('ytplayer_1', 'uj_84ChwWNA');
            player_2 = YoutubeIframe('ytplayer_2', 'xV8MCsv8rzo');
            player_3 = YoutubeIframe('ytplayer_3', 'rU0dEceaitc');
            player_4 = YoutubeIframe('ytplayer_4', 'YQzfvJaJhzE');
            player_5 = YoutubeIframe('ytplayer_5', 'suFK-qhGXkY');
            player_6 = YoutubeIframe('ytplayer_6', 'G82XxNYkES8');
            player_7 = YoutubeIframe('ytplayer_7', 'G82XxNYkES8');
        }

        // Step 3: add ytplayer_X func Stop Video
        $('ul.youtube-link-list li').on('click', function() {
            // player_0.stopVideo();
            // player_1.stopVideo();
            // player_2.stopVideo();
            // player_4.stopVideo();
            // player_5.stopVideo();

            player_0.stopVideo();
            player_1.stopVideo();
            player_2.stopVideo();
            player_3.stopVideo();
            player_4.stopVideo();
            player_5.stopVideo();
            player_6.stopVideo();
            player_7.stopVideo();

            // Step 4: ytplayer_X func Play Video when click to li of ul.youtube-link-list
            switch($(this).attr('el')) {
                // case '0': player_0.playVideo(); break;
                // case '1': player_1.playVideo(); break;
                // case '2': player_2.playVideo(); break;
                // case '4': player_4.playVideo(); break;
                // case '5': player_5.playVideo(); break;
                case '0': player_0.playVideo(); break;
                case '1': player_1.playVideo(); break;
                case '2': player_2.playVideo(); break;
                case '3': player_3.playVideo(); break;
                case '4': player_4.playVideo(); break;
                case '5': player_5.playVideo(); break;
                case '6': player_6.playVideo(); break;
                case '7': player_7.playVideo(); break;
                default: console.log('Not found video youtube!');
            }
        });
        function YoutubeIframe(id_show, id_video) {
            return new YT.Player(id_show, {
                width: 625,
                height: 380,
                videoId: id_video,
                playerVars: {
                    disablekb: 1,
                    enablejsapi: 1,
                    iv_load_policy: 3,
                    modestbranding: 0,
                    showinfo: 0,
                    rel: 0
                }
            });
        }

        /**
         * Show modal aliorders when first time
         */
        $(window).on('load',function(){
	        <?php
	        if (!empty(session('shopId'))){
	        $cacheObj = new \Illuminate\Support\Facades\Cache();
	        $keyClickedAliOderBanner = 'clickedAliOderBannerV5_'.session('shopId');
	        if (!$cacheObj::has($keyClickedAliOderBanner) or empty($cacheObj::get($keyClickedAliOderBanner))){
	        $cacheObj::forever($keyClickedAliOderBanner, 1);
	        ?>
            $('#RedirectToAliOdersdModal').modal('show');
	        <?php }
	        }?>
        });
    </script>
@endsection


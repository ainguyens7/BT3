<script src="{{ URL::asset('libs/jquery-3.1.1.min.js')  }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"
        integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb"
        crossorigin="anonymous"></script>
<script src="{{ URL::asset('libs/bootstrap.min.js')  }}"></script>
<script src="{{ URL::asset('libs/jquery.validate.min.js')  }}"></script>
<script src="https://unpkg.com/axios@0.16.2/dist/axios.min.js"></script>
<script src="{{ URL::asset('libs/chosen.jquery.min.js')  }}"></script>
<script src="{{ URL::asset('libs/jquery.mCustomScrollbar.min.js')  }}"></script>
<script src="{{ URL::asset('libs/bootstrap-multiselect.js')  }}"></script>
<script src="{{ URL::asset('libs/bootstrap-notify.min.js')  }}"></script>
<script src="{{ URL::asset('libs/jquery.mousewheel.min.js')  }}"></script>
<script type="text/javascript" src="{{ URL::asset('libs/vue.min.js')}}"></script>
<script src="{{ mix_cdn('js/backend/main.js')  }}"></script>


<script>
    function successInstall() {
        location.reload();
        $('.extension-chrome-wrap').hide();
    }

    function failureInstall() {
        window.open('https://chrome.google.com/webstore/detail/ali-reviews-aliexpress-re/bbaogjaeflnjolejjcpceoapngapnbaj', '_blank');
    }
    $(function(){
        $('.extension-install-btn').on('click', function () {
            chrome.webstore.install('https://chrome.google.com/webstore/detail/bbaogjaeflnjolejjcpceoapngapnbaj',successInstall, failureInstall);
        });
    });
</script>

<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '95100348886',
            xfbml      : true,
            version    : 'v2.6'
        });
    };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
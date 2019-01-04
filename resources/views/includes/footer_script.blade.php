<script src="{{ mix_cdn('js/vendor.js')  }}"></script>
<script src="{{ mix_cdn('js/bundle.js')  }}"></script>

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

    // Enable pusher logging - don't include this in production
    var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
        forceTLS: true
    });

    var channel = pusher.subscribe('message-channel-{{ session('shopId') }}');
    channel.bind('App\\Events\\DeleteAllReviewsPusherEvent', function(data) {
//        $('#alertDeleteReviewsSuccessModal').modal({
//            show: "true"
//        });

        $(".alert.animated").remove();

        $.notify(
            {
                message: "{{ __('reviews.deleteSuccessAllReviewsInShop') }}"
            },
            {
                z_index: 999999,
                timer: 1000 * 60 * 60 * 24,
                type: "success"
            }
        );
    });



</script>

@include('includes/fb_sdk')
@include('includes/modal_alert_delete_reviews_success')
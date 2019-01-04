<script type="text/x-template"  id="step-review-app">
    <!-- STEP 4 -->
    <div class="rating-app-alireview-wrap">
        <img src="{{ cdn('images/badge-with-a-star.png') }}" alt="Badge with a star">
        <div class="rating-app">
            <i class="demo-icon icon icon-star-2"></i>
            <i class="demo-icon icon icon-star-2"></i>
            <i class="demo-icon icon icon-star-2"></i>
            <i class="demo-icon icon icon-star-2"></i>
            <i class="demo-icon icon icon-star-2"></i>
        </div>
        <p>If this app is useful for your business, please rate us on Shopify App Store</p>
        <button type="button" class="ars-btn" v-on:click="addReviewsApp($event)">RATE</button>

        <div class="rating-app-you-know">
            <strong>Did you know: </strong>When you rate our app, you will have a good backlink to your store from Shopify
        </div>
    </div>
</script>
<script type="text/x-template" id="get-review-step1">
    <div v-if="is_add_review === 'success'">
        <!-- STEP 1 -->
        <h2>Get AliExpress Review</h2>
        {{--<p style="text-align: center;">Add more reviews to this product.</p>--}}
        <div class="review-product-image">
        <span>
            <img v-bind:src="image" alt="">
        </span>
        </div>
        <h3 class="title-product">@{{ title }}</h3>

        <div v-show="is_reviews  == 1 || is_reviews  == true" class="alert alert-warning fz11" role="alert">
            <div class="ars-field">This product had reviews already.</div>
            <label class="ars-checkbox" for="del_add_new">
                <input id="del_add_new" type="checkbox" v-model="choiceTypeGetReview" v-on:click="clickTypeGetReview()">
                <span class="ars-checked"> <i class="demo-icon icon-ok-4"></i></span>
                Click here to remove all imported reviews in this product
            </label>
        </div>
        <div class="ars-field">
            <input type="text" v-on:keyup.enter="goStep(2)" autofocus  v-model="aliexpress" name="aliexpress-url" v-on:change="onChangeAliexpress" placeholder="Enter URL of AliExpress product">
        </div>
        <!--Show error-->
        <div class="error">@{{ valid_aliexpress }}</div>
        <!--End show error-->
        <div class="add-reviews-btn-wrap" @click="goStep(2)">
            <a class="ars-btn" id="input-link-aliexpress">NEXT <i class="demo-icon icon-right-open-mini"></i></a>
        </div>
    </div>

    <div v-else-if="is_add_review === 'error_products'">
        <div class="opps-add-review">
            <div class="review-product-image">
        <span  style="border: none">
            <img src="{{ url('images/backend/img-opps.png') }}" alt="">
        </span>
            </div>
            <h3 class="title-product">Opps, seems like you are out of products that can get reviews<br>
                Upgrade to get more reviews for your products</h3>

            <div class="text-center">
                <a href="{{ route('apps.upgrade') }}" title="Upgrade to Premium version" class="btn-upgrade">Upgrade to Premium version</a>
            </div>

            <div class="add-reviews-btn-wrap">
                <a class="ars-btn" data-dismiss="modal">Cancel</a>
            </div>
        </div>
    </div>

    <div v-else-if="is_add_review === 'error_browser'">
        <div class="opps-add-review">
            <div class="review-product-image">
        <span  style="border: none">
            <img src="{{ url('images/backend/img-opps.png') }}" alt="">
        </span>
            </div>
            <h3 class="title-product">Opps, Add Reviews function only works with <b style="color: #f53663">Google Chrome</b>  for Win/Mac<br>
                Please change your device/browser and try again</h3>

            <div class="add-reviews-btn-wrap">
                <a class="ars-btn" data-dismiss="modal">Cancel</a>
            </div>
        </div>
    </div>

    <div v-else-if="is_add_review === 'error'">
        <div class="opps-add-review">
            <div class="review-product-image">
                <span  style="border: none">
                    <img src="{{ url('images/backend/img-opps.png') }}" alt="">
                </span>
            </div>

            <h3 class="title-product">
                Opps, An error<br>
                please reload page and try again
            </h3>

            <div class="text-center">
                <a href="" title="Reload" class="button button--primary w-130px btn-upgrade">Reload</a>
            </div>
            
            <div class="add-reviews-btn-wrap">
                <a class="button button--primary w-130px ars-btn" data-dismiss="modal">Cancel</a>
            </div>
        </div>
    </div>
</script>
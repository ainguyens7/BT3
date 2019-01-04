<div class="modal ali-modal ali-modal--noline fade" id="modalImportReview" role="dialog" aria-labelledby="modalImportReview">
    <div class="modal-dialog" role="document" style="max-width: 530px;">
        <div class="modal-content">
            {{-- <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="material-icons icon" aria-hidden="true">clear</i>
                </button>
            </div> --}}
            <div class="modal-body pad-0">
                <step2 
                    v-show="step==2"
                    v-bind:title="title"
                    v-bind:image="image"
                    v-bind:valid_aliexpress="valid_aliexpress"
                    v-bind:is_add_review="is_add_review"
                    v-bind:aliexpress="aliexpress"
                    v-bind:settings="settings"
                    v-bind:product_id="product_id"
                    v-bind:shop_id="shop_id"
                    v-bind:all_country="all_country"
                    v-bind:is_reviews="is_reviews"
                    v-bind:type="type"
                    v-on:show-step="showStep"
                    v-on:settings="settingsGetReview"
                ></step2>
                <step3_get_aliexpress v-show="step=='3_aliexpress'"></step3_get_aliexpress>
                <step3_save_database v-show="step=='3_save'"></step3_save_database>
                <step4_no_review v-on:show-step="showStep" v-show="step=='4_no_review'"></step4_no_review>
                <step4_success v-show="step=='4_success'" v-bind:reload="reload" v-bind:total_review="total_review" v-bind:review_url="review_url"></step4_success>
                <step_review_app v-show="step=='step_review_app'"></step_review_app>
            </div>
        </div>
    </div>
</div>
@include('products.get_reviews.step2')
@include('products.get_reviews.step3_get_aliexpress')
@include('products.get_reviews.step3_save_database')
@include('products.get_reviews.step4_no_review')
@include('products.get_reviews.step4_success')
@include('products.get_reviews.step_review_app')

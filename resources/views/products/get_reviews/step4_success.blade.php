<script type="text/x-template"  id="get-review-step4-success">
    <!-- STEP 4 -->
    <div class="add-review-finish">
        <div class="text-center">
            <div class="modal-body__logo mar-b-30">
                <img src="{{ asset('images/modals/check.png') }}">
            </div>

            <div class="modal-body__content">
                <p class="note mar-b-5">Congratulation! <span class="total-review">@{{ total_review }}</span> reviews has been imported</p>
                <p class="mar-b-30">Notice: It'll take 10-15sec to show on your page. Please wait.</p>
            </div>
        </div>

        <div class="text-center">
            <a class="button button--primary mar-r-5" v-bind:href="review_url" v-if="reload">Check Now</a>

            <button type="button" class="button button--default mar-l-5" data-dismiss="modal">+ Adding More</button>
        </div>
    </div>
</script>
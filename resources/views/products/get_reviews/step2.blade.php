<script type="text/x-template" id="get-review-step2">
    <!-- success -->
    <form v-if="is_add_review === 'success'">
        <div class="modal-body__content">
            <div class="modal-body__title fz21 fw-bold color-dark-blue mar-b-25 text-center">Import AliExpress Reviews</div>

            <div class="row mar-b-20">
                <div class="col-sm-3">
                    <img v-bind:src="image" class="img-rounded" width="95px" style="border: solid 1px #ccc;">
                </div>
                <div class="col-sm-9">
                    <p class="title-note mar-b-10 text-over-2">@{{ title }}</p>
                    <div class="form-group">
                        <input type="text" v-model="aliexpress" name="aliexpress-url" placeholder="Enter URL of AliExpress product" class="form-control mar-b-10" v-on:keydown.enter.prevent="goStep('3_aliexpress')">
                    </div>
                    <p class="style-error" v-if="valid_aliexpress !== ''">@{{valid_aliexpress}}</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-xs-6">
                    <div class="fz13">
                        <label class="wrap-custom-box fw500 mar-b-15"> Use 
                            <span class="fw-bold color-pink mar-l-5 mar-r-5">{{ __('settings.title') }}</span>
                            <input type="checkbox" checked="checked" v-model="isDefaultFillter">
                            <span class="checkmark-ckb"></span>
                        </label>
                    </div>
                </div>

                <div class="col-xs-6" v-show="is_reviews == 1 || is_reviews == true">
                    <div class="fz13">
                        <label class="wrap-custom-box fw500 mar-b-15"> Remove all imported reviews
                            <input id="del_add_new" type="checkbox" v-model="choiceTypeGetReview">
                            <span class="checkmark-ckb"></span>
                        </label>
                    </div>
                </div>
            </div>

            <div v-show="!isDefaultFillter">
            <hr class="mar-t-10">

            <div class="row mar-b-5">
                <div class="col-sm-6">
                    <p class="title-note mar-b-20 ">{{ __('settings.label_max_number_review') }}</p>
                    <div class="form-group">
                        <input type="number" v-model="settings.get_max_number_review" class="form-control fw600 color-dark-blue">
                    </div>
                    <p class="style-error" v-if="valid_get_max_number_review !== ''" v-html="valid_get_max_number_review"></p>
                </div>

                <div class="col-sm-6">
                    <p class="title-note mar-b-20 ">{{ __('settings.label_language_review') }}</p>
                    <div class="form-group" style="height: 37px; mar-b-20">
                        <select class="multiselect-search" id="get-review-filter-country"  multiple="multiple" tabindex="5">
                            <option v-for="(name, code) in all_country" v-bind:selected="isCountrySelected(code)" v-bind:value="code" v-text="name"></option>
                        </select>
                    </div>
                    <p class="style-error" v-text="valid_country_get_review"></p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-xs-6">
                    <p class="title-note mar-b-20 ">{{ __('settings.label_only_star') }}</p>
                    <div class="fz13">
                        <label class="wrap-custom-box fw500 mar-b-15"> Get <span class="fw-bold color-pink mar-l-5 mar-r-5">5 stars</span> reviews
                            <input type="checkbox" v-model="settings.get_only_star" value="5" :checked="existsValue(settings.get_only_star,5)">
                            <span class="checkmark-ckb"></span>
                        </label>

                        <label class="wrap-custom-box fw500 mar-b-15"> Get
                            <span class="fw-bold color-pink mar-l-5 mar-r-5">4 stars</span> reviews
                            <input type="checkbox" v-model="settings.get_only_star" value="4" :checked="existsValue(settings.get_only_star,4)">
                            <span class="checkmark-ckb"></span>
                        </label>
                    </div>
                </div>

                <div class="col-xs-6">
                    <p class="title-note mar-b-20 ">{{ __('settings.label_picture_option') }}</p>
                    <div class="fz13">
                        <label class="wrap-custom-box fw500 mar-b-15"> Reviews with
                            <span class="fw-bold color-pink mar-l-5 mar-r-5">picture</span>
                            <input v-model="settings.get_only_picture" type="checkbox" name="get_picture_option" tabindex="3" value="true" :checked="existsValue(settings.get_only_picture, true)">
                            <span class="checkmark-ckb"></span>
                        </label>

                        <label class="wrap-custom-box fw500 mar-b-15"> Reviews
                            <span class="fw-bold color-pink mar-l-5 mar-r-5">without picture</span>
                            <input v-model="settings.get_only_picture" type="checkbox" name="get_picture_option" tabindex="3" value="false" :checked="existsValue(settings.get_only_picture, false)">
                            <span class="checkmark-ckb"></span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="row mar-t-10">
                <div class="col-xs-6">
                    <p class="title-note mar-b-20 ">{{ __('settings.label_content_options') }}</p>
                    <div class="fz13">
                        <label class="wrap-custom-box fw500 mar-b-15"> Reviews with
                            <span class="fw-bold color-pink mar-l-5 mar-r-5">content</span>
                            <input id="checkbox-with-content" v-model="settings.get_only_content" type="checkbox" name="get_content_option" tabindex="3" value="true" :checked="existsValue(settings.get_only_content, true)">
                            <span class="checkmark-ckb"></span>
                        </label>

                        <label class="wrap-custom-box fw500 mar-b-15"> Reviews
                            <span class="fw-bold color-pink mar-l-5 mar-r-5">without content</span>
                            <input v-model="settings.get_only_content" type="checkbox" name="get_content_option" tabindex="3" value="false" :checked="existsValue(settings.get_only_content, false)">
                            <span class="checkmark-ckb"></span>
                        </label>
                    </div>
                </div>

                <div class="col-xs-6">
                    <p class="title-note mar-b-20">{{ __('settings.label_translate_info') }}</p>
                    <div class="fz13 d-flex">
                        <label class="wrap-custom-box fw500 mar-b-5" for="is_language_translate">
                            <input @click="checkTranslateReviews(settings.translate_reviews)" v-model="settings.translate_reviews" type="checkbox" :checked="settings.translate_reviews == 1" id="is_language_translate">
                            <span class="checkmark-ckb"></span>
                        </label>

                        <div>
                            <select class="form-control select2 unsearch mar-b-0" id="translate-language">
                                <option v-for="(name, code) in getTranslateCountryAliexpress" v-bind:selected="code == settings.language_translate_reviews" v-bind:value="code" v-text="name"></option>
                            </select>
    
                            <p class="mar-t-5">
                                using Aliexpress translate
                                <img src="{{ cdn('images/icons/help-icon.png') }}" class="mar-l-5" data-toggle="tooltip-import" title="Please notice that the Aliexpress site may change to this language after imported reviews">
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mar-b-20 mar-t-5">
                <div class="col-md-12 fz11"><i>{{ __('settings.default_get_all') }}</i></div>
            </div>

            </div>

            <div class="mar-t-15">
                <button class="button button--default pull-left" data-dismiss="modal">Cancel</button>
                <button class="button button--primary pull-right" @click.prevent="goStep('3_aliexpress')" v-on:enter="goStep('3_aliexpress')" onfocus="true">Import Review</button>
            </div>
        </div>
        
        <div class="clearfix"></div>
    </form>



    <!-- error_browser -->
    <div v-else-if="is_add_review === 'error_browser'">
        <div class="modal-body__logo mar-b-30">
            <img src="{{ cdn('images/modals/chrome.png') }}" alt="Domain.com">
        </div>

        <div class="modal-body__content fw-bold fz13">
            <p class="note">Opps, Add Reviews function only works with
                <span class="color-pink">Google Chrome</span> for Win/Mac. Please change your device/browser and try again</p>
        </div>
        <a href="https://www.google.com/chrome/" target="_blank" class="btn btn-primary fw-bold fz13">Download Chrome</a>
        <button type="button" class="button button--default w-130px" data-dismiss="modal">Cancel</button>
    </div>
    
    <!-- error -->
    <div class="text-center" v-else-if="is_add_review === 'error'">
        <div class="modal-body__title fz21 fw-bold color-dark-blue mar-b-10">Opps, An error</div>
        <div class="modal-body__content">
            <p class="sub-note mar-b-35">Please reload page and try again.</p>
        </div>
        <button type="button" class="button button--default w-130px mar-r-5" data-dismiss="modal">Cancel</button>
        <a href="{{ route('products.list') }}" class="button button--primary w-130px mar-l-5">Reload</a>
    </div>
</script>
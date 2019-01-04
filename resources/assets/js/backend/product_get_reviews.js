let starSetting = [];

jQuery.fn.extend({
  loading: function(parents) {
    // $("#modalWaiting").modal("show");
  },
  loading_finish: function() {
    // $("#modalWaiting").modal("hide");
  }
});

Vue.component("step2", {
  template: "#get-review-step2",
  props: [
    "title",
    "image",
    "settings",
    "valid_aliexpress",
    "aliexpress",
    "type",
    "product_id",
    "shop_id",
    "all_country",
    "is_add_review",
    "is_reviews"
  ],
  data() {
    return {
      isDefaultFillter: true,
      is_error: 0,
      valid_get_max_number_review: "",
      valid_country_get_review: "",
      choiceTypeGetReview: false,
      // aliexpressCountry: {
      //   www: "English",
      //   en: "English",
      //   ru: "Russian",
      //   pt: "Portuguese",
      //   es: "Spanish",
      //   fr: "French",
      //   de: "German",
      //   it: "Italian",
      //   nl: "Dutch",
      //   tr: "Turkish",
      //   ja: "Japanese",
      //   ko: "Korean",
      //   th: "Thai",
      //   vi: "Vietnamese",
      //   ar: "Arabic",
      //   he: "Hebrew",
      //   pl: "Polish"
      // }
      // aliexpressCountryNoti: {
      //   ru: "Russian",
      //   pt: "Portuguese",
      //   es: "Spanish",
      //   fr: "French",
      //   de: "German",
      //   it: "Italian",
      //   nl: "Dutch",
      //   tr: "Turkish",
      //   ja: "Japanese",
      //   ko: "Korean",
      //   th: "Thai",
      //   vi: "Vietnamese",
      //   ar: "Arabic",
      //   he: "Hebrew",
      //   pl: "Polish"
      // }
    };
  },
  computed: {
    getTranslateCountryAliexpress: function() {
      // v-bind:selected="code == settings.language_translate_reviews" 
      // this.country_code = this.settings.language_translate_reviews;
      return GenerateReview.aliexpressCountry;
    }
    // checkAliexpressCountryCode: function() {
    //   let aliExpressCountryNotification = Object.keys(
    //     this.aliexpressCountryNoti
    //   );
    //   return (
    //     aliExpressCountryNotification.indexOf(
    //       this.detectCountry(this.aliexpress)
    //     ) >= 0
    //   );
    // }
  },
  methods: {
    // clickTypeGetReview: function() {
    //   if (this.choiceTypeGetReview === true) {
    //     GenerateReview.type = "del_add_new";
    //   } else {
    //     GenerateReview.type = "add_in";
    //   }
    // },
    isCountrySelected: function(code) {
      return ( GenerateReview.settings.country_get_review.indexOf(code) > -1 ? true : false )
    },
    onChangeAliexpress: function() {
      GenerateReview.aliexpress = this.aliexpress;
    },
    checkTranslateReviews: function(translate) {
      this.settings.translate_reviews = translate;
    },
    validate: function(settings) {
      this.is_error = 0;
      this.valid_get_max_number_review = "";
      this.valid_country_get_review = "";
      this.valid_aliexpress = "";

      // Validate max number review
      if ( settings.get_max_number_review === "" || parseInt(settings.get_max_number_review) <= 0 || parseInt(settings.get_max_number_review) > 1500 ) {
        this.valid_get_max_number_review = lang_get_reviews.valid_get_max_number_review;
        this.is_error++;
      }

      // Validate country
      if ( typeof settings.country_get_review === "undefined" || settings.country_get_review.length <= 0 ) {
        this.is_error++;
        this.valid_country_get_review = lang_get_reviews.valid_country_get_review;
      }

      // Validate url aliexpress
      if ( !this.validateAliLink(this.aliexpress) ) {
        this.valid_aliexpress = lang_get_reviews.validate_aliexpress;
        this.is_error++;
      }

      if (this.is_error > 0) {
        return false;
      }

      return true;
    },
    validateAliLink: function(aliexpress) {
      var pattern = /^((http:\/\/)|(https:\/\/))((www\.)|(ru\.)|(pt\.)|(es\.)|(fr\.)|(de\.)|(it\.)|(nl\.)|(tr\.)|(ja\.)|(ko\.)|(th\.)|(vi\.)|(ar\.)|(he\.)|(pl\.))(aliexpress\.com\/)((item\/)|(store\/product\/)|(item\/\/)|(item\/-\/))\w/;
      if (!pattern.test(aliexpress)) return false;

      return true;
    },
    // detectCountry: function(aliexpress) {
    //   var str = aliexpress.slice(0, 12);
    //   var country = str.match(
    //     /www|ru|pt|es|fr|de|it|nl|tr|ja|ko|th|vi|ar|he|pl/
    //   );
    //   if (country !== null) return country[0];
    //   else return null;
    // },
    goStep: function(step) {
      if (step === "3_aliexpress") {
        // this.valid_aliexpress = "";
        // if (!this.validateAliLink(this.aliexpress)) {
        //   this.valid_aliexpress = lang_get_reviews.validate_aliexpress;
        //   return false;
        // }
        if (!this.validate(this.settings)) {
          return false;
        }

        this.$emit("show-step", step);
        this.$emit("url-aliexpress", this.aliexpress);
        const get_only_star = this.settings.hasOwnProperty("get_only_star") ? this.settings.get_only_star : [];

        starSetting = get_only_star;
        if (get_only_star.length === 0) {
          this.settings.get_only_star = ["1", "2", "3", "4", "5"];
        }
        const get_only_picture = this.settings.hasOwnProperty("get_only_picture") ? this.settings.get_only_picture : [];

        if (get_only_picture.length === 0) {
          this.settings.get_only_picture = ["true", "false"];
        }

        const get_only_content = this.settings.hasOwnProperty(
          "get_only_content"
        )
          ? this.settings.get_only_content
          : [];

        if (get_only_content.length === 0) {
          this.settings.get_only_content = ["true", "false"];
        }

        this.$emit("settings", this.settings);
        GenerateReview.settings.get_only_content = get_only_content;
        
        //Post beforeImportReviewEvent
        GenerateReview.settings = this.isDefaultFillter ? JSON.parse($("#get-review-settings").val()) : GenerateReview.settings;
        GenerateReview.settings.is_default_filter_setting = this.isDefaultFillter;
        GenerateReview.aliexpress = GenerateReview.convertUrlAliexpressDefaultTranslate(this.aliexpress, (this.isDefaultFillter ? this.settings.language_translate_reviews : GenerateReview.country_code));
        this.choiceTypeGetReview === true ?  GenerateReview.type = "del_add_new" : GenerateReview.type = "add_in";
        
        if(this.isDefaultFillter != true) {
          GenerateReview.settings.translate_language_selected = GenerateReview.country_code;
        }

        $.ajax({
          type: "POST",
          url: appUrl + "/api/prepareImportReview",
          dataType: "json",
          data: {
            shopId: GenerateReview.shop_id
          }
        });

        window.postMessage(
          {
            type_message: "aliReview",
            aliExpress: GenerateReview.aliexpress,
            settings: GenerateReview.settings
          },
          "*"
        );
      }
    },
    checkedOnlyRating: function(args, value) {
      if (args !== null && typeof args !== "undefined") {
        return args.indexOf(value) >= 0;
      }
    },
    existsValue: function(args, value) {
      if (args === null || typeof args === "undefined") {
        return false;
      }
      return args.includes(value);
    }
  }
});

Vue.component("step3_get_aliexpress", {
  template: "#get-review-step3-get-aliexpress"
});

Vue.component("step3_save_database", {
  template: "#get-review-step3-save-database"
});

Vue.component("step4_no_review", {
  template: "#get-review-step4-no-review",
  methods: {
    goStep: function(step) {
      this.$emit("show-step", step);
    }
  }
});

Vue.component("step4_success", {
  props: ["review_url", "total_review", "reload"],
  template: "#get-review-step4-success"
});

/**
 * Component Reviews App
 */
Vue.component("step_review_app", {
  template: "#step-review-app",
  methods: {
    addReviewsApp: function(event) {
      event.preventDefault();

      window.open(
        "https://apps.shopify.com/ali-reviews?reveal_new_review=true",
        "_blank"
      );

      $("#addReviewsModal").modal("hide");

      $.ajax({
        type: "post",
        url: appUrl + "/review_app",
        dataType: "json",
        data: {},
        success: function(data, statusText, xhr) {
          console.log("Review app!");
        }
      });
    }
  }
});

//Country change
$(function() {
  $("#modalImportReview").on("shown.bs.modal", function() {
    $('[data-toggle="tooltip-import"]').tooltip({
      html: true,
      container: "body"
    });

    $("#translate-language").select2({
      minimumResultsForSearch: Infinity
    });

    $('#translate-language').on('change.select2', function() {
      GenerateReview.country_code = $(this).val();
    });

    $("body").addClass("modal-open");
    // var countryGetReview = GenerateReview.settings.country_get_review;
    $("#get-review-filter-country").multiselect({
      nonSelectedText: "Choose Language",
      enableFiltering: false,
      includeSelectAllOption: true,
      enableCaseInsensitiveFiltering: true,
      templates: {
        li: '<li class="checkList"><label class="wrap-custom-box"> one<input type="checkbox" checked="checked"><span class="checkmark-ckb"></span></label></li>',
        filter: '<li class="multiselect-item filter"><div class="input-group" style="width: 100%; padding-right: 10px;"><input class="form-control multiselect-search" type="text"></div></li>',
        filterClearBtn: false
      },
      onChange: function(option, checked) {
        // countryGetReview = $("#get-review-filter-country").val();
        GenerateReview.settings.country_get_review = $("#get-review-filter-country").val();
      },
      onSelectAll: function() {
        // countryGetReview = $("#get-review-filter-country").val();
        GenerateReview.settings.country_get_review = $("#get-review-filter-country").val();
      },
      onDeselectAll: function() {
        // countryGetReview = $("#get-review-filter-country").val();
        GenerateReview.settings.country_get_review = $("#get-review-filter-country").val();
      },
      onInitialized: function(select, container) {
        $(".multiselect, .multiselect-search").css("opacity", "1");
      }
    });

    $(".multiselect-search + div .multiselect-container .wrap-custom-box").each(function(index) {
      $(this).append('<span class="checkmark-ckb"></span>');

      $(this).click(function(e) {
        e.stopPropagation();
      });
      }
    );
  });

  $(".selectActionAllReviews2").on("change", function() {
    var dataProductId = $(this).attr("data-product_id");
    var modal = "#" + $(this).val();

    if ($(this).val() === "") return false;

    $(modal).on("shown.bs.modal", function() {
      $('input[name="product_id"]', $(modal)).val(dataProductId);
    });

    var originalModal = $(modal).clone();
    $(modal).on("hidden.bs.modal", function() {
      $(modal).remove();
      var myClone = originalModal.clone();
      $("body").append(myClone);
    });

    $(modal).modal({
      show: "true"
    });

    $(this)
      .val(null)
      .trigger("change");
  });

  $("body").delegate("#formDeleteAllReview2", "submit", function(e) {
    e.preventDefault();

    var obj = $(this);
    $.ajax({
      type: "post",
      url: appUrl + "/review/all-action",
      dataType: "json",
      data: {
        action: "delete",
        product_id: $('input[name="product_id"]', obj).val(),
        _token: $('input[name="_token"]', obj).val()
      },
      beforeSend: function() {
        obj.attr("disabled", true).css("opacity", 0.5);
      },
      success: function(data, statusText, xhr) {
        if (data.status && data.status == "success") {
          $.notify(
            {
              message: data.message
            },
            {
              z_index: 999999,
              timer: 2000,
              type: "success"
            }
          );

          window.location.reload();
        } else {
          obj.attr("disabled", false).css("opacity", 1);
          $.notify(
            {
              message: data.message
            },
            {
              z_index: 999999,
              timer: 2000,
              type: "danger"
            }
          );
        }
      },
      error: function() {
        obj.attr("disabled", false);
        $.notify(
          {
            message: lang_reviews.notice_error
          },
          {
            z_index: 999999,
            timer: 2000,
            type: "danger"
          }
        );
      }
    });
  });

  $("body").delegate("#formPublishAllReview2", "submit", function(e) {
    e.preventDefault();

    var obj = $(this);
    $.ajax({
      type: "post",
      url: appUrl + "/review/all-action",
      dataType: "json",
      data: {
        action: "publish",
        product_id: $('input[name="product_id"]', obj).val(),
        _token: $('input[name="_token"]', obj).val()
      },
      beforeSend: function() {
        obj.attr("disabled", true).css("opacity", 0.5);
      },
      success: function(data, statusText, xhr) {
        if (data.status && data.status == "success") {
          $.notify(
            {
              message: data.message
            },
            {
              z_index: 999999,
              timer: 2000,
              type: "success"
            }
          );

          window.location.reload();
        } else {
          obj.attr("disabled", false).css("opacity", 1);
          $.notify(
            {
              message: data.message
            },
            {
              z_index: 999999,
              timer: 2000,
              type: "danger"
            }
          );
        }
      },
      error: function() {
        obj.attr("disabled", false);
        $.notify(
          {
            message: lang_reviews.notice_error
          },
          {
            z_index: 999999,
            timer: 2000,
            type: "danger"
          }
        );
      }
    });
  });

  $("body").delegate("#formUnPublishAllReview2", "submit", function(e) {
    e.preventDefault();

    var obj = $(this);
    $.ajax({
      type: "post",
      url: appUrl + "/review/all-action",
      dataType: "json",
      data: {
        product_id: $('input[name="product_id"]', obj).val(),
        action: "unpublish",
        _token: $('input[name="_token"]', obj).val()
      },
      beforeSend: function() {
        obj.attr("disabled", true).css("opacity", 0.5);
      },
      success: function(data, statusText, xhr) {
        if (data.status && data.status == "success") {
          $.notify(
            {
              message: data.message
            },
            {
              z_index: 999999,
              timer: 2000,
              type: "success"
            }
          );

          window.location.reload();
        } else {
          obj.attr("disabled", false).css("opacity", 1);
          $.notify(
            {
              message: data.message
            },
            {
              z_index: 999999,
              timer: 2000,
              type: "danger"
            }
          );
        }
      },
      error: function() {
        obj.attr("disabled", false);
        $.notify(
          {
            message: lang_reviews.notice_error
          },
          {
            z_index: 999999,
            timer: 2000,
            type: "danger"
          }
        );
      }
    });
  });

  $(document.body).on("click", function(event) {
    var highlight_review = $("body").hasClass("step-highlight-add-review");
    $(".sidebarDashboard").tooltip("show", {
      container: ".sidebar-container"
    });
    if (highlight_review) {
      $("body").removeClass("step-highlight-add-review");
      $("body").addClass("step-highlight-dashboard");
    } else {
      var highlight_dashboard = $("body").hasClass("step-highlight-dashboard");

      if (highlight_dashboard) {
        $("body").removeClass("step-highlight-open");
        $("body").removeClass("step-highlight-dashboard");
      }
    }
  });
});

window.GenerateReview = new Vue({
  el: "#page-list-product-get-review",
  data: {
    total_review: 0,
    product_total_reviews: 0,
    product_avg_reviews: 0,
    step: 1,
    title: "",
    image: "",
    aliexpress: "",
    shop_id: "",
    product_id: "",
    type: "add_in",
    settings: {},
    all_country: {},
    country_code: 'www',
    review_url: "",
    reload: "",
    valid_aliexpress: "",
    is_reviews: false,
    count_total_get_reviews: 0,
    is_reviews_app: 0,
    is_add_review: "",
    count_display_modal_vote: 3,
    aliexpressCountry: {
      www: "English",
      ru: "Russian",
      pt: "Portuguese",
      es: "Spanish",
      fr: "French",
      de: "German",
      it: "Italian",
      nl: "Dutch",
      tr: "Turkish",
      ja: "Japanese",
      ko: "Korean",
      th: "Thai",
      vi: "Vietnamese",
      ar: "Arabic",
      he: "Hebrew"
    }
  },
  mounted: function() {
    this.settings = $("#get-review-settings").val() !== undefined ? JSON.parse($("#get-review-settings").val()) : {};

    if ( this.settings.get_only_star === null || typeof this.settings.get_only_star === "undefined" ) {
      this.settings.get_only_star = [];
    }

    if ( this.settings.country_get_review === null || typeof this.settings.country_get_review === "undefined" ) {
      this.settings.country_get_review = [];
    }

    if ( this.settings.language_translate_reviews === null || typeof this.settings.language_translate_reviews === "undefined" ) {
      this.settings.language_translate_reviews = 'www';
    } else {
      this.country_code = this.settings.language_translate_reviews;
    }

    this.shop_id = $("#get-review-shop-id").val();
    this.is_reviews_app = $("#shop-current-is-reviews-app").val();
    this.all_country = $("#all-country").val() !== undefined ? JSON.parse($("#all-country").val()) : [];

    //Handle Modal close
    $("#modalImportReview").on("hidden.bs.modal", function() {
      if ( GenerateReview.count_total_get_reviews === GenerateReview.count_display_modal_vote && !parseInt(GenerateReview.is_reviews_app) ) {
        GenerateReview.step = "step_review_app";
        $("#modalImportReview").modal();
        GenerateReview.is_reviews_app = 1;
      } else {
        $("#modalImportReview").modal("hide");
      }
    });
  },
  methods: {
    showModal: function(e) {
      this.is_add_review = 1;
      this.product_total_reviews = e.total_reviews;
      this.product_avg_reviews = e.avg_reviews;
      this.valid_aliexpress = "";
      this.total_review = 0;
      // this.$emit("show-modal");
      this.step = 2;
      this.aliexpress = "";
      this.title = e.title;
      this.product_id = e.id;
      if (e.image === null) this.image = appUrl + "/images/avatar.jpg";
      else this.image = e.image;
      var product_id = this.product_id;
      const { extensionId = "" } = window;
      if (!extensionId || (extensionId && extensionId != extensionIdDefine)) {
        // show pop up extension
        $("#modalInstallChromeExtension").modal("show");
        return;
      }
      $.ajax({
        type: "get",
        url: appUrl + "/checkAddReview",
        dataType: "json",
        data: {
          product_id: this.product_id
        },
        beforeSend: function() {
          $(".add-reviews-btn-" + product_id).loading();

          // Icon loading
          $(".add-reviews-btn-" + product_id).addClass("wrap-loading");
          $(".add-reviews-btn-" + product_id).prepend(
            '<div class="lds-dual-ring"></div>'
          );
          // END: Icon loading
        },
        success: function(data, statusText, xhr) {
          $(".add-reviews-btn-" + product_id).loading_finish();

          // Icon loading
          $(".add-reviews-btn-" + product_id).removeClass("wrap-loading");
          $(".add-reviews-btn-" + product_id)
            .find(".lds-dual-ring")
            .remove();
          // END: Icon loading

          const {
            status = false,
            message = "",
            review_link = "",
            has_imported_review = false
          } = data;
          
          GenerateReview.is_add_review = message;
          GenerateReview.is_reviews = has_imported_review;
          GenerateReview.aliexpress = review_link;

          if (GenerateReview.is_add_review === "error_products") {
            $("#convertingUserToPro").modal();

            return false;
          }

          if (GenerateReview.is_add_review == "error_browser") {
            $("#modalImportReview").modal({
              backdrop: "static",
              keyboard: false
            });
          } else {
            $("#modalImportReview").modal({
              backdrop: "static",
              keyboard: false
            });
          }
        },
        error: function() {
          GenerateReview.is_add_review = "error";
          $("#modalImportReview").modal({
            backdrop: "static",
            keyboard: false
          });
        }
      });
    },
    showStep: function(step) {
      this.step = step;
    },
    settingsGetReview: function(settings) {
      this.settings = settings;
    },
    urlAliexpress: function(aliexpress) {
      this.aliexpress = aliexpress;
    },
    convertUrlAliexpressDefaultTranslate: function(aliexpress, country_code = 'www') {
      const pattHTTP_HTTPS = /^(https?:\/\/)/gi; 
      let http;

      if(aliexpress.match(pattHTTP_HTTPS)) {
        http = aliexpress.split("://")[0];
        aliexpress = aliexpress.split("://")[1];
      } 

      const countryCode = country_code === "en" ? "www" : country_code;
      const tempDomain = aliexpress.split('.aliexpress.com/')[1];
      const newDomain = `${http}://${ countryCode }${ countryCode ? '.' : '' }aliexpress.com/${tempDomain}`;
      
      console.log('[convertUrlAliexpressDefaultTranslate]', newDomain);
      // document.cookie = 'aep_usuc_f=; path=/; domain=.aliexpress.com; expires=Expires=Thu, 01 Jan 1970 00:00:01 GMT';
      
      return newDomain;
    },
    handleGetReviewExtension: function(reviewObj) {
      this.review_url = appUrl + "/manage-reviews/" + this.product_id;
      this.settings.get_only_star = starSetting;
      if (reviewObj.argsReviews.length <= 0) {
        Intercom('trackEvent', 'import-failed');
        GenerateReview.step = "4_no_review";
        return false;
      }
      
      var dataBody = {
        type: this.type,
        shop_id: this.shop_id,
        product_id: this.product_id,
        reviewObj: JSON.stringify(reviewObj.argsReviews),
        get_max_number_review: this.settings.get_max_number_review,
        review_link: this.aliexpress
      };
      //Post review save to data
      $.ajax({
        type: "POST",
        url: appUrl + "/saveReviewAliexpress",
        data: dataBody,
        beforeSend: function() {
          GenerateReview.step = "3_save";
        },
        success: function(response) {
          if (response.status) {
            GenerateReview.reload = false;
            if ($("#page-list-product-get-review.product-review-wrap").length) {
              GenerateReview.reload = true;
            }
            GenerateReview.total_review = response.totalReviews;
            let product_total_reviews = parseInt(
              $(
                "#product-item-" +
                  GenerateReview.product_id +
                  " .product-item-total-review"
              ).text()
            );
            if (GenerateReview.type === "add_in") {
              product_total_reviews =
                product_total_reviews + parseInt(response.totalReviews);
            } else {
              product_total_reviews = parseInt(response.totalReviews);
            }
            $(
              "#product-item-" +
                GenerateReview.product_id +
                " .product-item-total-review"
            ).text(product_total_reviews);
            $(
              "#product-item-" + GenerateReview.product_id + " .alr-rating"
            ).attr("value", response.avgReviews);

            $(".alr-rating-" + GenerateReview.product_id).rating(
              "rate",
              response.avgReviews
            );

            GenerateReview.step = "4_success";
            GenerateReview.reload = true;
            GenerateReview.count_total_get_reviews++;
          } else {
              Intercom('trackEvent', 'import-failed');
              GenerateReview.step = "4_no_review";
          }
        },
        error: function(error) {
          $.notify(
            {
              message: "Server Error, please contact support@fireapps.io"
            },
            {
              z_index: 999999,
              timer: 2000,
              type: "danger"
            }
          );
          $("#addReviewsModal").modal("hide");
        }
      });
    },
    getStarRating: function(rating) {
      rating = rating <= 5 || rating >= 0 ? rating : 0;
      rating = Math.floor(rating * 2) / 2;

      let str = '<div class="rate-it">';
      let strRating = '<i class="demo-icon icon-star-2 rated"></i>';
      let strRatingHalf = '<i class="demo-icon icon-star-half-alt rated"></i>';
      let strRatingEmpty = '<i class="demo-icon icon-star-empty-1 rated"></i>';

      for (let i = 0; i < 5; i++) {
        if (rating === i + 0.5) {
          str += strRatingHalf;
        } else if (rating > i) {
          str += strRating;
        } else str += strRatingEmpty;
      }

      str += "</div>";

      return str;
    }
  }
});

$(function(){
    // bind change event to select
    $('select[name=is_review]').on('change', function () {
        var condition  =  $(this).val() === '1' ||  $(this).val() ==='-1';

        if (!condition) {
          return   window.location = appUrl+'/manage-reviews';
        }

        window.location = appUrl+'/manage-reviews?is_review='+$(this).val();

    });
});

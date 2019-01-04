let mix = require("laravel-mix");
mix.disableNotifications();

mix.sass("resources/assets/scss/main.scss", "public/css/main.css");
mix.sass("resources/assets/scss/aliorder_import_review_extension.scss", "public/css/aliorder_import_review_extension.css");
mix.sass(
  "resources/assets/scss/vendor_fe_shop.scss",
  "public/css/frontend/vendor.css"
);
mix
  .sass(
    "resources/assets/sass/rating-demo-style2.scss",
    "public/css/frontend/list.css"
  )
  .sass(
    "resources/assets/sass/rating-demo-style5.scss",
    "public/css/frontend/grid.css"
  );
mix.copy("resources/assets/images", "public/images");
mix.copy("resources/assets/img", "public/images");
mix
  .scripts(
    [
      "resources/assets/js/libs/jquery-3.1.1.min.js",
      "resources/assets/js/libs/bootstrap.min.js",
      "resources/assets/js/libs/jquery.validate.min.js",
      "resources/assets/js/libs/chosen.jquery.min.js",
      "resources/assets/js/libs/jquery.mousewheel.min.js",
      "resources/assets/js/libs/bootstrap-multiselect.js",
      "resources/assets/js/libs/bootstrap-notify.min.js",
      "resources/assets/js/libs/vue.min.js",
      "resources/assets/js/libs/pusher.min.js",
      "resources/assets/libs/select2/select2.min.js",
      "resources/assets/libs/rating/bootstrap-rating.min.js",
      "resources/assets/libs/owl-carousel/js/owl.carousel.min.js",
      "resources/assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js",
      "resources/assets/libs/tagsinput/jquery.tagsinput.js",
      "resources/assets/libs/masonry/masonry.min.js",
      "resources/assets/libs/bootstrap-datetimepicker/js/moment.js",// dùng chung với datetimepicker
      "resources/assets/libs/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js",
      "resources/assets/libs/codemirror/codemirror.js",
      "resources/assets/libs/codemirror/javascript.js",
      "resources/assets/libs/codemirror/sublime.js",
      "resources/assets/libs/light-box/lightbox.js"
    ],
    "public/js/vendor.js"
  )
  .version();

mix
  .js(
    [
      "resources/assets/js/backend/main.js",
      "resources/assets/js/backend/settings.js",
      "resources/assets/js/backend/product_get_reviews.js",
      "resources/assets/js/backend/action_all_reviews_product.js",
      "resources/assets/js/backend/manage_product_review.js",
      "resources/assets/js/backend/uploadfile.js",
      "resources/assets/js/backend/reviews_approve.js",
      "resources/assets/js/backend/discount.js",
      "resources/assets/js/backend/realview_translation_page.js",
      "resources/assets/js/backend/realview_theme_settings_page.js"
      
    ],
    "public/js/bundle.js"
  )
  .version();
mix
  .js(
    [
      "resources/assets/js/frontend/comment.js"
    ],
    "public/js/frontend/comment.js"
  )
  .version();

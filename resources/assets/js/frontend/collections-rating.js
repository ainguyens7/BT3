// Add code: <div product-id="{{ product.id }}" class="arv-collection arv-collection--{{ product.id }}"></div>

import config from './config';

function insertRating(eleDisplay, product_id, avg_rating, text_review) {
  const eleRating =
    '<div class="alrv-prod-rating-' + product_id + '"><input type="hidden" class="alr-rating" data-filled="alr-icon-star" data-empty="alr-icon-star" data-fractions="1" data-readonly value="' +
    avg_rating +
    '"/><span class="alrv-prod-rating__text">' +
    text_review +
    "</span></div>";

  eleDisplay.insertAdjacentHTML("afterbegin", eleRating);
}

export default function addRatingCollection() {
  const divProdCollections = document.getElementsByClassName("arv-collection");
  const shopUrl = window.Shopify.shop;
  const product_ids = Array.prototype.map.call(divProdCollections, function(
    item
  ) {
    return item.getAttribute("product-id");
  });

  $.ajax({
    method: "GET",
    url: config.shop_url + "/comment/get_summary_star_collection",
    data: {
      product_ids: product_ids,
      shop_domain: shopUrl,
      isAdminLogin: true
    },
    success: function(result) {
      const avg_rating = result.hasOwnProperty("avg_star")
        ? result.avg_star
        : {};
      const total_review = result.hasOwnProperty("total_review")
        ? result.total_review
        : {};
      // extract key from result.avg_rating
      const avg_key = Object.keys(avg_rating);
      avg_key.length &&
        Promise.all(
          avg_key.map(function(productId) {
            if (avg_rating[productId] > 0) {
              const eleDisplay = document.getElementsByClassName(
                "arv-collection--" + productId
              );
              const text = (total_review[productId] == 0 || total_review[productId] == 1) ? result["text_review_singular"] : result["text_review_plural"];
              const text_review = total_review[productId] + " " + text;
              
              for(var i = 0; i < eleDisplay.length; i++) {
                if(eleDisplay[i].childElementCount == 0) {
                  insertRating(
                    eleDisplay[i],
                    productId,
                    avg_rating[productId],
                    text_review
                  );
                }
              }
            }
          })
        ).then(function() {
          $('.alr-rating').length && $('.alr-rating').rating();
        });
    }
  });
}

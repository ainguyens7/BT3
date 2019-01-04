var countReview = 0;
var argsReviews = [];

/**
 * Get review multi page
 * @param urlAliexpress
 * @param settings
 * @returns {boolean}
 */
function getContent(urlAliexpress, settings) {
  console.log(urlAliexpress);
  console.log(settings);
  //Reset var init
  argsReviews = [];
  countReview = 0;
  var xmlHttp = new XMLHttpRequest();
  xmlHttp.open("GET", urlAliexpress, false); // false for synchronous request
  xmlHttp.send(null);
  var parser = new DOMParser();
  var html = $.parseHTML(xmlHttp.responseText);
  $("#temp_div_full").remove();
  var divadd_1 =
    "<div id='temp_div_full' style='visibility:hidden;position:fixed;top:-5000px;'></div>";
  $("body").append(divadd_1);
  $("#temp_div_full").append(html);
  //Check if cannot find iframe feedback return false
  if (document.querySelector("#feedback iframe") == null) {
    window.location.href =
      "javascript:GenerateReview.handleGetReviewExtension({status: false}); void 0";
    $("#temp_div_full").remove();
    return false;
  }
  var urlFeedBack = document
    .querySelector("#feedback iframe")
    .getAttribute("thesrc");
  var urlFeedBack = "https:" + urlFeedBack;
  var xmlHttp = new XMLHttpRequest();
  xmlHttp.open("GET", urlFeedBack, false); // false for synchronous request
  xmlHttp.send(null);
  var parser = new DOMParser();
  var html = $.parseHTML(xmlHttp.responseText);
  $("#temp_div").remove();
  var divadd =
    "<div id='temp_div' style='visibility:hidden;position:fixed;top:-5000px;'></div>";
  $("body").append(divadd);
  $("#temp_div").append(html);
  let max_page = 1;
  // var list_page = $("#temp_div .ui-pagination-navi .ui-goto-page");
  // list_page.each(function() {
  //   if (parseInt($(this).attr("pageno")) > max_page) {
  //     max_page = parseInt($(this).attr("pageno"));
  //   }
  // });
  const pagination = $(
    "#temp_div #simple-pager .ui-pagination-front .ui-label"
  );
  const str = pagination.length && pagination.text();
  const pages = str && str.includes("/") ? str.split("/") : [];
  max_page = pages.length ? pages.pop() : 1;
  // console.log(max_page);
  // max_page = (max_page <= 200) ? max_page : 200;

  var product_id = $("#productId").val();
  var ownerMemberId = $("#ownerMemberId").val();
  var translateReviews = "N";
  if (settings.translate_reviews) var translateReviews = "Y";

  for (var i = 1; i <= max_page; i++) {
    var xmlHttp = new XMLHttpRequest();
    var url =
      "https://feedback.aliexpress.com/display/productEvaluation.htm?productId=" +
      product_id +
      "&ownerMemberId=" +
      ownerMemberId +
      "&companyId=231015615&page=" +
      i +
      "&memberType=seller&translate=" +
      translateReviews;
    xmlHttp.open("GET", url, false); //false for synchronous request
    xmlHttp.send(null);
    var parser = new DOMParser();
    var html = $.parseHTML(xmlHttp.responseText);
    $("#temp_div2").remove();
    var divadd =
      "<div id='temp_div2' style='visibility:hidden;position:fixed;top:-5000px;'></div>";
    $("body").append(divadd);
    $("#temp_div2").append(html);
    //Check if settings get number review great total review is break loop

    $("#temp_div2 .feedback-item").each(function() {
      var country = $(this)
        .find(".css_flag")
        .html();
      var content_review = $(this)
        .find(".buyer-feedback span")
        .html();
      var time = $(this)
        .find(".r-time")
        .html();
      var rating_text = $(this)
        .find(".star-view span")
        .attr("style");
      var photo = $(this)
        .find(".r-photo-list")
        .html();
      var list_photo = $(this).find(
        ".r-photo-list .util-clearfix .pic-view-item"
      );
      var list_url = [];
      list_photo.each(function() {
        var link_img = $(this)
          .find("img")
          .attr("src");
        list_url.push(link_img);
      });
      var rating = parseInt(rating_text.replace(/%|width:/g, "")) / 20;

      var itemReview = {
        user_country: country,
        review_content: content_review,
        review_date: time,
        review_image_str: list_url,
        review_star: rating
      };

      itemReview = filterReview(settings, itemReview);
      if (itemReview) {
        argsReviews.push(itemReview);
        countReview++;
      }
    });
    if (settings.get_max_number_review <= countReview) break;
  }

  $("#temp_div").remove();
  $("#temp_div2").remove();
  $("#temp_div_full").remove();
  // console.log(argsReviews);
  argsReviews = JSON.stringify(argsReviews);
  setTimeout(function() {
    //Emit response to app
    window.location.href =
      "javascript:GenerateReview.handleGetReviewExtension({argsReviews : " +
      argsReviews +
      "}); void 0";
  }, 1000);
}

/**
 * Filter review
 * @param settings
 * @param itemReview
 * @returns {*}
 */
function filterReview(settings, itemReview) {
  if (
    settings.get_only_star == null ||
    typeof settings.get_only_star == "undefined"
  )
    settings.get_only_star = ["1", "2", "3", "4", "5"];

  if (
    Boolean(settings.get_only_picture) &&
    Boolean(itemReview.review_image_str.length) !=
      Boolean(settings.get_only_picture)
  )
    return false;

  if (
    Boolean(settings.get_only_content) &&
    Boolean(itemReview.review_content.length) !=
      Boolean(settings.get_only_content)
  )
    return false;

  if (
    settings.get_only_star.length > 0 &&
    settings.get_only_star.indexOf(String(itemReview.review_star)) <= -1
  )
    return false;

  if (
    settings.country_get_review.indexOf(String(itemReview.user_country)) <= -1
  )
    return false;

  return itemReview;
}

/**
 * Listener event post from app
 */
window.addEventListener(
  "message",
  function(event) {
    if (event.source != window) return;
    if (event.data.type_message && event.data.type_message == "aliReview") {
      getContent(event.data.aliExpress, event.data.settings);
    }
  },
  false
);

function checkExceptKeyword() {}

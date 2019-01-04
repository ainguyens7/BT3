import { getReviewFromUrlandSetting } from "./oberlo/modules/getReview";

function getContent(aliexpress = "", settings) {
  console.log("request");
  console.group();
  console.log(settings);
  console.log(aliexpress);
  console.groupEnd();
  !aliexpress &&
    `javascript:GenerateReview.handleGetReviewExtension({argsReviews : ${JSON.stringify(
      []
    )}})`;
  aliexpress &&
    getReviewFromUrlandSetting(aliexpress, settings).then(result => {
      const reviews = JSON.stringify(result);
      reviews &&
        (window.location.href = `javascript:GenerateReview.handleGetReviewExtension({argsReviews : ${
          reviews
        }})`);
      !reviews && console.warn("no review");
    });
}
window.addEventListener(
  "message",
  event => {
    console.log("extension recieve request");
    if (event.source != window) return;
    const { data: { type_message = "", aliExpress, settings } } = event;
    type_message == "aliReview" && getContent(aliExpress, settings);
  },
  false
);

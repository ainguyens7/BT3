import view from "views/noReview.hbs";
import { tryGetReviewAgain } from "./helper";
import toast from "./showToast";

export default function showModalNoReview() {
  toast(chrome.i18n.getMessage("titleNoReview"));
}

import view from "views/loading.hbs";
import toast from "./showToast";
export default function showModalLoadingReview() {
  toast("Importing reviews from AliExpress...", "info");
}

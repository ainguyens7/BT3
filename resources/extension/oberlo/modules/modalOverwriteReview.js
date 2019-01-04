import view from "views/confirmOverwriteReview.hbs";
import { idModalOverwriteReview } from "./constant";

export default function modalOverwriteReview(data) {
  const alireviewModalWrapper = document.querySelector(
    ".alireview-modal-wrapper"
  );
  alireviewModalWrapper.innerHTML = view(data);
  $(idModalOverwriteReview).modal({ show: true });
}

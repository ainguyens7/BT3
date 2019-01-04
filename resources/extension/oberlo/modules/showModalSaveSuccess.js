import view from "views/addReviewSuccess.hbs";
import { tryGetReviewAgain } from "./helper";

export default function showModalSaveSuccess(data) {
  const alireviewModalWrapper = document.querySelector(
    ".alireview-modal-wrapper"
  );
  const currentModal = alireviewModalWrapper.querySelector(".modal");
  const modalContent = currentModal.querySelector(".modal-body");
  modalContent.innerHTML = view(data);
  tryGetReviewAgain();
}

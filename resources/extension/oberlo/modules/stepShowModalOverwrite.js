import modalOverwriteReview from "./modalOverwriteReview";
import { idModalOverwriteReview, gotoModalSetting } from "./constant";
import stepShowModalSetting from "./stepShowModalSetting";
import { clearModalBackdrop, setStatusOverwriteReview } from "./helper";

function handleClickNext() {
  const elCheckbox = document.querySelector("#del_add_new");
  elCheckbox && setStatusOverwriteReview(elCheckbox.checked);
}

function clearAllModalOverwriteBefore() {
  const elModalOverwrite = $("#modalOverwriteReview");
  elModalOverwrite.length && elModalOverwrite.remove();
  const elModalBackdrop = $(".modal-backdrop");
  elModalBackdrop.length && elModalBackdrop.remove();
}

export default function setShowModalOverwrite(data) {
  console.log("step show modal overwrite");
  const { setting, productId } = data;
  clearAllModalOverwriteBefore();
  modalOverwriteReview(data);
  $(idModalOverwriteReview).on("shown.bs.modal", () => {
    $("#next-setting").on("click", () => {
      $(idModalOverwriteReview).modal("hide");
      handleClickNext();
      stepShowModalSetting(productId, setting);
    });
  });
}

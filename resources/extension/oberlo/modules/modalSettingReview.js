import view from "views/modalSettingReview.hbs";
import { idModalSettingReview } from "./constant";

function clearAllModalelModalSetting() {
  const elModalSetting = $("#idModalSettingReview");
  elModalSetting.length && elModalSetting.remove();
  const elModalBackdrop = $(".modal-backdrop");
  elModalBackdrop.length && elModalBackdrop.remove();
}

export default function modalSettingReview(data) {
  clearAllModalelModalSetting();
  const alireviewModalWrapper = document.querySelector(
    ".alireview-modal-wrapper"
  );
  alireviewModalWrapper.innerHTML = view(data);
  $(idModalSettingReview).modal({ show: true, backdrop: "static" });
  $("#get-review-filter-country").multiselect({
    nonSelectedText: "Choose Language",
    buttonContainer: '<div class="selected-parents-container"></div>',
    enableFiltering: true,
    includeSelectAllOption: true,
    enableCaseInsensitiveFiltering: true,
    onChange: function(option, checked) {},
    onSelectAll: function() {},
    onDeselectAll: function() {}
  });
}

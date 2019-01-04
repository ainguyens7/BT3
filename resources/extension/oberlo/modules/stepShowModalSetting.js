import modalSettingReview from "./modalSettingReview";
import {
  getShopifyDomain,
  getStatusOverwriteReview,
  getStorageAliLink,
  setStatusOverwriteReview,
  hideContentModal,
  shouldShowToastGetReview
} from "./helper";
import { idModalSettingReview } from "./constant";
import { getReviewFromUrlandSetting } from "./getReview";
import showModalLoadingReview from "./showModalLoadingReview";
import showModalNoReview from "./showModalNoReview";
import showModalSaveSuccess from "./showModalSaveSuccess";
import toast from "./showToast";

import Api from "./api";

function hideCurrentModalSetting() {
  const elModalSetting = $("#modalSettingReview");
  const elModalOverwrite = $("#modalOverwriteReview");

  elModalSetting.length && elModalSetting.modal("hide");
  elModalOverwrite.length && elModalOverwrite.modal("hide");
  elModalSetting.on("hidden.bs.modal", e => {
    elModalSetting.remove();
  });

  elModalOverwrite.on("hidden.bs.modal", e => {
    elModalOverwrite.remove();
  });
}

function handlerShownModalSetting(el, productId) {
  const t = el;
  let settings = {
    get_only_star: []
  };

  $("#get-review").on("click", handleClickGetReview);

  function handleClickGetReview(e) {
    e.preventDefault();

    hideCurrentModalSetting();
    showModalLoadingReview();

    getSettings();
    const { aliexpress_link, ...rest } = settings;
    const shop = getShopifyDomain();
    getReviewFromUrlandSetting(aliexpress_link, rest)
      .then(reviews => {
        const reviewLength = reviews.length;
        const statusOverwrite = getStatusOverwriteReview();
        if (!reviewLength) {
          showModalNoReview();
          return;
        } else {
          const type = statusOverwrite == "true" ? "del_add_new" : "false";
          const payload = {
            reviewObj: JSON.stringify(reviews),
            type
          };
          return Api.saveReview(shop, productId, payload);
        }
      })
      .then(result => {
        if (!result) {
          showModalNoReview();
          return;
        }
        const { status = "" } = result;
        setStatusOverwriteReview();
        if (status) {
          const { totalReviews, url } = result;
          const link = `<a href="${url}?shop_domain=${
            shop
            }" target="_blank">Manage now</a>`;
          const message = `${chrome.i18n.getMessage("reviewImported")} ${link}`;
          toast(message, "success");
        } else {
          const { message = "" } = result;
          message && toast(message);
        }
      })
      .catch(error => {
        console.log(error);
        toast(chrome.i18n.getMessage("e03"));
      });
  }

  function getSettings() {
    getCheckboxStar();
    getCheckboxPicture();
    getCheckboxContent();
    getCheckboxTranslateEnglish();
    getMaxNumberReview();
    getCountry();
    getIsOverwriteReview();
    getAlilink();
    getExceptKeword();
  }

  function getCheckboxStar() {
    getCheckbox5Star();
    getCheckbox4Star();
  }

  function getCheckbox5Star() {
    const el = t.find("#checkbox-review-5-star");
    settings["get_only_star"].push(el.val());
  }

  function getCheckbox4Star() {
    const el = t.find("#checkbox-review-4-star");
    settings["get_only_star"].push(el.val());
  }

  function getCheckboxPicture() {
    const el = t.find("#checkbox-with-picture");
    settings["get_only_picture"] = el.prop("checked");
  }

  function getCheckboxContent() {
    const el = t.find("#checkbox-with-content");
    settings["get_only_content"] = el.prop("checked");
  }

  function getCheckboxTranslateEnglish() {
    const el = t.find("#checkbox-translate-english");
    settings["checkbox_translate_english"] = el.prop("checked");
  }

  function getMaxNumberReview() {
    const el = t.find("#max-number-review");
    settings["max_number_review"] = el
      ? parseInt(el.val(), 10) > 1500 ? 1500 : parseInt(el.val(), 10)
      : 200;
  }

  function getCountry() {
    const el = t.find("#get-review-filter-country");
    settings["review_filter_country"] = el.val();
  }

  function getIsOverwriteReview() {
    const status = getStatusOverwriteReview();
    settings["is_overwrite_review"] = status;
  }

  function getAlilink() {
    const aliLink = getStorageAliLink();
    settings["aliexpress_link"] = aliLink;
  }

  function getExceptKeword() {
    const elExceptKeywrod = t.find('[name="except_keyword"]');
    settings["except_keyword"] = elExceptKeywrod.val();
  }
}

export default function stepShowModalSetting(productId, settings) {
  console.log("step show modal setting");
  const shop = getShopifyDomain();
  console.log(`get settings shop ${shop}`);
  Api.getCountries()
    .then(countries => {
      const {
        setting: {
          get_max_number_review,
        get_only_content,
        get_only_picture,
        get_only_star = ['1', '2', '3', '4', '5'],
        country_get_review,
        except_keyword = ""
        }
      } = settings;
      modalSettingReview({
        get_max_number_review,
        get_only_content,
        get_only_picture,
        get_only_star,
        country_get_review,
        countries,
        except_keyword
      });
      $(idModalSettingReview).on("shown.bs.modal", event => {
        !document.body.classList.contains("modal-open") &&
          document.body.classList.add("modal-open");
        const el = $(idModalSettingReview);
        handlerShownModalSetting(el, productId);
      });
    })
    .catch(error => {
      toast(error.message);
      throw error;
    });
}

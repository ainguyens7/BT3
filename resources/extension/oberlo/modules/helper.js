import {
  alireviewModalWrapper,
  alireviewWrapper,
  isOverwriteReview,
  aliLink
} from "./constant";

import toast from "./showToast";

import { getAlilink } from "./request";

import Api from "./api";

export function convertStyleToStar(str) {
  const regex = /\d+/g;
  const exec = regex.exec(str);
  if (exec === null) {
    return 0;
  }
  return exec[0] / 20;
}

function extractShopifyDomain(el) {
  const attrHref = el.getAttribute("href");
  const url = new URL(attrHref);
  const { hostname } = url;
  return hostname;
}

export function getShopifyDomain() {
  const el = document.querySelector('[href$="myshopify.com/admin"]');
  if (!el) {
    toast(chrome.i18n.getMessage("e01"));
    throw new Error(chrome.i18n.getMessage("e01"));
  }
  return el && extractShopifyDomain(el);
}

export function addAlireviewModalWrapper() {
  const div = document.createElement("div");
  div.classList.add("alireview-modal-wrapper");
  document.body.appendChild(div);
}

export function findProductId(el) {
  try {
    const shop = getShopifyDomain();
    const linkProduct = el.find(`a[href^="https://${shop}"]`);
    if (!linkProduct) {
      toast(chrome.i18n.getMessage("e02"));
      throw new Error(chrome.i18n.getMessage("e02"));
    }
    const productLink = linkProduct.attr("href");
    const url = new URL(productLink);
    const { pathname } = url;
    const productId = pathname.replace("/admin/products/", "");
    return productId;
  } catch (error) {
    toast(error.message);
    throw error;
  }
}

export function addAlireviewWrapper() {
  document.body.classList.add(alireviewWrapper);
}

export async function shouldShowModalOverwriteReview(shop, productId) {
  console.log(
    `check status get reviews shop ${shop} and productId ${productId}`
  );
  try {
    const result = await Api.checkHasReview(shop, productId);
    const keys = Object.keys(result);
    return keys.length && parseInt(result.is_reviews) > 0;
  } catch (error) {
    console.log(error);
  }
}

export function findProductTitle(parent) {
  const link = parent.find('[id^="openInAliButton_"]');
  if (!link.length) {
    toast(chrome.i18n.getMessage("e02"));
    throw new Error(chrome.i18n.getMessage("e02"));
  }
  return link.text().trim();
}
export function findProductImage(parent) {
  const div = parent.find(".product-page-image");
  const img = div.find("img");
  if (img) {
    return img.attr("src");
  }
  return "";
}

export function clearModalBackdrop() {
  $(".modal-backdrop").remove();
}

export function setStatusOverwriteReview(status = false) {
  localStorage.setItem(isOverwriteReview, status);
}

export function getStatusOverwriteReview() {
  return localStorage.getItem(isOverwriteReview);
}

export function setAliLink(link) {
  localStorage.setItem(aliLink, link);
}

export function getStorageAliLink() {
  return localStorage.getItem(aliLink);
}

export function findAliLink(el) {
  const elOpenAliButton = el.find('[id^="openInAliButton_"]');
  if (!elOpenAliButton.length) {
    toast(chrome.i18n.getMessage("e02"));
    throw new Error(chrome.i18n.getMessage("e02"));
  }
  const attr = elOpenAliButton && elOpenAliButton.attr("id");
  const productId = attr && attr.replace("openInAliButton_", "");
  getAlilink(productId, function(response) {
    const { url } = response;
    try {
      const urlParse = new URL(url);
      const link =
        urlParse.searchParams.get("dl_target_url") ||
        urlParse.searchParams.get("ulp");
      setAliLink(link);
    } catch (error) {
      toast(error.message);
      throw error;
    }
  });
}

export function hideContentModal() {
  const alireviewModalWrapper = document.querySelector(
    ".alireview-modal-wrapper"
  );
  const currentModal =
    alireviewModalWrapper && alireviewModalWrapper.querySelector(".modal");
  const modalContent =
    currentModal && currentModal.querySelector(".modal-body");
  modalContent && (modalContent.innerHTML = "");
}

export function tryGetReviewAgain() {
  const el = $("#try-add-review");
  const currentModal = $(".alireview-modal-wrapper .modal");
  el.on("click", event => {
    event.preventDefault();
    currentModal.modal("hide");
  });
}

export function shouldShowToastGetReview() {
  const elModalSetting = $("#modalSettingReview");
  return elModalSetting && elModalSetting.is(":visible");
}

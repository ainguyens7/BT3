import { alireviewProductPage } from "./constant";
import { getShopifyDomain, findProductId } from "./helper";
import view from "../views/alireviewIcon.hbs";
import Api from "./api";

function createElementAlireviewWrap() {
  const el = document.createElement("div");
  el.classList.add("alireview-icon-wrap");
  return el;
}

function createElementAlireview(data) {
  return view(data);
}

async function startRenderAlireviewIcon(productId, productItem, shop) {
  const elAliIconWrap = createElementAlireviewWrap();
  try {
    const productReview = (await Api.checkHasReview(shop, productId)) || {};

    const { is_reviews = 0, url_manage_review = "" } = productReview;
    const imported =
      parseInt(is_reviews, 10) && url_manage_review ? true : false;
    const elAli = createElementAlireview({
      imported: imported,
      url: `${url_manage_review}?shop_domain=${shop}`,
      id: productId
    });
    productItem.classList.add(alireviewProductPage);
    productItem.appendChild(elAliIconWrap);
    elAliIconWrap && (elAliIconWrap.innerHTML = elAli);
  } catch (error) {
    console.log(error);
  }
}
export default function addAlireviewIcon() {
  const productPage = document.querySelectorAll(".products-page");
  const shop = getShopifyDomain();
  productPage.length &&
    productPage.forEach(async productItem => {
      const elAliSupplier = productItem.querySelector(".ali-supplier");
      const productId = elAliSupplier && findProductId($(productItem));
      productId &&
        (await startRenderAlireviewIcon(productId, productItem, shop));
    });
}

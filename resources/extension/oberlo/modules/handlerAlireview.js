import {
  findProductId,
  getShopifyDomain,
  shouldShowModalOverwriteReview,
  findProductTitle,
  findProductImage,
  findAliLink
} from "./helper";

import setShowModalOverwrite from "./stepShowModalOverwrite";
import stepShowModalSetting from "./stepShowModalSetting";
import stepCheckAccount from "./stepCheckAccount";
import showModalNotPermit from "./showModalNotPermit";

function handlerAlireview(event) {
  const el = event;
  const parent = el.parent();
  const productId = findProductId(parent);
  const prodTitle = findProductTitle(parent);
  const prodImage = findProductImage(parent);
  findAliLink(parent);
  const shop = encodeURIComponent(getShopifyDomain());
  stepCheckAccount(shop).then(result => {
    const { status } = result;
    if (status === "success") {
      const { setting } = result;
      shouldShowModalOverwriteReview(shop, productId).then(_result => {
        if (_result) {
          setShowModalOverwrite({
            title: prodTitle,
            image: prodImage,
            setting,
            productId
          });
        } else {
          stepShowModalSetting(productId, setting);
        }
      });
    } else {
      const { message, url } = result;
      showModalNotPermit({ message, url });
    }
  });
}

export default function registerListenerAlireviewIcon() {
  $(document).on("click", ".js-alireview-action-get-review", function(event) {
    const parent = $(this).parents(".alireview-icon-wrap");
    parent.length && handlerAlireview(parent);
  });
}

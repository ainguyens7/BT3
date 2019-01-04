const linkAff = "https://s.click.aliexpress.com/e/Fu3zjQn";

const addCartButton = document.querySelector("#j-add-cart-btn");
const buyAll = document.querySelector('input[value="Buy All"]');

const iframeElem = `<iframe src="${linkAff}" height="0" width="0" class="nhg-ext-aff"></iframe>`;

addCartButton && addCartButton.insertAdjacentHTML("beforebegin", iframeElem);
buyAll && buyAll.insertAdjacentHTML("beforebegin", iframeElem);

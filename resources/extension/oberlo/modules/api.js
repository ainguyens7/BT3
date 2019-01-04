import request, { put } from "modules/request";

function Api() {
  this.origin = API_ORIGIN;
}

Api.prototype.checkHasReview = async function checkHasReview(shop, productId) {
  const url = `${this.origin}/shops/${shop}/products/${productId}`;
  try {
    const result = await request(url);
    const { status } = result;
    if (status === 200 && result.ok) {
      const json = await result.json();
      return json;
    }
    throw new Error(`Fetch request error ${url}`);
  } catch (error) {
    throw error;
  }
};

Api.prototype.getSetting = async function getSetting(shop) {
  const shopDomain = encodeURIComponent(shop);
  const url = `${this.origin}/settings/${shopDomain}`;
  try {
    const response = await request(url);
    const { status } = response;
    if (status === 200 && response.ok) {
      const result = await response.json();
      return result;
    }
    throw new Error(`Fetch request error ${url}`);
  } catch (error) {
    throw error;
  }
};

Api.prototype.getCountries = async function getCountries() {
  const url = `${this.origin}/countries`;
  try {
    const response = await request(url);
    const { status } = response;
    if (status === 200 && response.ok) {
      const result = await response.json();
      return result;
    }
    throw new Error(`Fetch request error ${url}`);
  } catch (error) {
    throw error;
  }
};

Api.prototype.saveReview = async function saveReview(shop, productId, data) {
  const shopDomain = encodeURIComponent(shop);
  const url = `${this.origin}/shops/${shopDomain}/products/${productId}`;
  try {
    const response = await put(url, data);
    const { status } = response;
    if (status === 200 && response.ok) {
      const result = await response.json();
      return result;
    }
    throw new Error(`Fetch request error ${url}`);
  } catch (error) {
    throw error;
  }
};
export default new Api();

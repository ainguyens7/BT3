import request from "./request";
import { convertStyleToStar } from "modules/helper";
import log from "modules/log";

function extractCountry(elWrapper) {
  const el = elWrapper.querySelector(`.user-country`);
  return el ? el.firstElementChild.innerText : "";
}

function extractContent(elWrapper) {
  const el = elWrapper.querySelector(".buyer-feedback");
  return el ? el.firstElementChild.innerText : "";
}

function extractStar(elWrapper) {
  const el = elWrapper.querySelector(".star-view");
  const child = el && el.firstElementChild;
  const styleAttr = child && child.getAttribute("style");
  return convertStyleToStar(styleAttr);
}

function extractImage(elWrapper) {
  const elListViewImage = elWrapper.querySelectorAll(".pic-view-item");
  return Array.prototype.map.call(elListViewImage, item => {
    return item && item.getAttribute("data-src");
  });
}

function extracTime(elWrapper) {
  const elTime = elWrapper.querySelector(".r-time");
  return elTime ? elTime.innerText : new Date().toISOString();
}

function extractReviewObject(elWrapper) {
  const country = extractCountry(elWrapper);
  const content = extractContent(elWrapper);
  const star = extractStar(elWrapper);
  const img = extractImage(elWrapper);
  const time = extracTime(elWrapper);
  return {
    user_country: country,
    review_content: content,
    review_star: star,
    review_image_str: img,
    review_date: time
  };
}

export function parseReview(docText) {
  const domParser = new DOMParser();
  const doc = domParser.parseFromString(docText, "text/html");
  const elFeedbackItem = doc.querySelectorAll(".feedback-item");
  return Array.prototype.map.call(elFeedbackItem, item => {
    return extractReviewObject(item);
  });
}

function extractMaxPage(docText) {
  const domParser = new DOMParser();
  const doc = domParser.parseFromString(docText, "text/html");
  const pagination =
    doc && doc.querySelector("#simple-pager .ui-pagination-front .ui-label");
  const str = pagination && pagination.innerText;
  const pages = str && str.includes("/") ? str.split("/") : [];
  const maxPage = pages.length ? pages.pop() : 1;
  return parseInt(maxPage, 10);
}

function createArray(from, to) {
  let arr = [];
  for (let i = from; i <= to; i++) {
    arr.push(i);
  }
  return arr;
}

export async function getReviewAllPage(url, setting) {
  try {
    const startTime = new Date().getTime();
    const docText = await getReviewFromUrl(url);
    const maxPage = extractMaxPage(docText);
    // get review first page
    const firstPageReview = parseReview(docText);
    const firstPageReviewFilter = filterReview(firstPageReview, setting);
    let result = [...firstPageReviewFilter];
    const max_number_review = setting.hasOwnProperty("get_max_number_review")
      ? setting.get_max_number_review
      : setting.hasOwnProperty("max_number_review")
        ? setting.max_number_review
        : 100;
    const translateReview = setting.hasOwnProperty("translate_reviews")
      ? setting.translate_reviews
      : setting.hasOwnProperty("checkbox_translate_english")
        ? setting.checkbox_translate_english
        : false;
    const translateReviewShortCut = translateReview
      ? "Y"
      : translateReview === "1" ? "Y" : "N";
    const maxNumberReview = parseInt(max_number_review, 10);
    if (maxPage <= 2) {
      return result.slice(0, maxNumberReview);
    }

    const pages = createArray(2, maxPage);
    let isFull = !(result.length <= maxNumberReview);
    let endIndex = Math.floor(maxNumberReview / 10);
    let startIndex = 0;
    while (!isFull) {
      const _pages = pages.slice(startIndex, endIndex);
      _pages.length === 0 && (isFull = true);
      _pages.length !== 0 &&
        (await Promise.all(
          _pages.map(async item => {
            let _url = new URL(url);
            _url.searchParams.set("page", item);
            _url.searchParams.set("translate", translateReviewShortCut);
            const d = await getReviewFromUrl(_url);
            const review = parseReview(d);
            const filter = filterReview(review, setting);
            console.log("reviews filtered", filter);
            if (result.length < maxNumberReview) {
              isFull = false;
              filter.forEach(review => {
                result.push(review);
              });
            } else {
              isFull = true;
            }
          })
        ));
      startIndex = endIndex;
      const remainingReview = maxNumberReview - result.length;
      const totalPageRemaining =
        remainingReview > 10 ? Math.floor(remainingReview / 10) : 1;
      endIndex =
        endIndex >= pages.length ? pages.length : endIndex + totalPageRemaining;
    }
    const endTime = new Date().getTime();
    result = result.slice(0, maxNumberReview);
    return result;
  } catch (error) {
    throw error;
  }
}

export async function getReviewFromUrl(url) {
  try {
    const result = await request(url);
    if (result.ok) {
      const html = await result.text();
      return html;
    }
    return "";
  } catch (error) {
    throw error;
  }
}

async function findIframeUrl(url) {
  try {
    const link = new URL(url);
    const response = await request(link);
    const docText = await response.text();
    const domParser = new DOMParser();
    const doc = domParser.parseFromString(docText, "text/html");
    const elFeedback = doc && doc.querySelector("#feedback");
    const iframe = elFeedback && elFeedback.querySelector("iframe");
    const thesrc = iframe && iframe.getAttribute("thesrc");
    const result = new URL(`https:${thesrc}`);
    return result;
  } catch (error) {
    throw error;
  }
}

function filterReview(list, setting) {
  const {
    get_only_picture,
    get_only_content,
    except_keyword = "",
    get_only_star = ["1", "2", "3", "4", "5"]
  } = setting;
  const review_filter_country = setting.hasOwnProperty("review_filter_country")
    ? setting.review_filter_country
    : setting.hasOwnProperty("country_get_review")
      ? setting.country_get_review
      : [];

  const getStar = get_only_star;
  const getOnlyPicture = get_only_picture;
  const getOnlyContent = get_only_content;

  const exceptKeyword = except_keyword ? except_keyword : "";
  // replace all white space character to pattern
  const _excepKeyword = exceptKeyword.replace(" ", "\\s");
  const regexKeyword =
    _excepKeyword &&
    new RegExp(`(${_excepKeyword.replace(/\,/gm, "|")})`, "gim");
  console.log("regexKeyword filter", regexKeyword);
  let _list;
  if (regexKeyword) {
    _list = list.filter(item => {
      console.log(
        item.review_content,
        item.user_country,
        item.review_content.search(regexKeyword)
      );
      return item.review_content.search(regexKeyword) === -1;
    });
  } else {
    _list = list;
  }
  console.log(_list);
  const filter = _list.filter(item => {
    if (getOnlyPicture && !getOnlyContent) {
      console.log("filter only picture");
      return item.review_image_str.length > 0;
    }
    if (getOnlyContent && !getOnlyPicture) {
      console.log("filter only content");
      return item.review_content.length > 0;
    }

    if (getOnlyPicture && getOnlyContent) {
      console.log("filter content and picture");
      return item.review_image_str.length > 0 && item.review_content.length > 0;
    }
    return true;
  });
  return filter.filter(item => {
    return (
      review_filter_country.includes(item.user_country) &&
      getStar.includes(String(item.review_star))
    );
  });
}

export async function getReviewFromUrlandSetting(url, setting) {
  console.log("shop settings", setting);
  console.log(`feedback url ${url}`);
  try {
    let aliUrl = url;
    if (url.includes("http://")) {
      aliUrl = url.replace("http://", "https://");
    }
    const src = await findIframeUrl(aliUrl);
    const translateReview = setting.hasOwnProperty("translate_reviews")
      ? setting.translate_reviews
      : setting.hasOwnProperty("checkbox_translate_english")
        ? setting.checkbox_translate_english
        : false;
    const { max_number_review = 100 } = setting;
    if (translateReview) {
      src.searchParams.set("translate", "Y");
    } else {
      src.searchParams.set("translate", "N");
    }
    const reviews = await getReviewAllPage(src, setting);
    return reviews;
  } catch (error) {
    console.log(error);
    return [];
  }
}

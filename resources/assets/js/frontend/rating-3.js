import lightbox from './libs/light-box/lightbox';

document.addEventListener("DOMContentLoaded", function() {
  var AlireviewApp = AlireviewApp || {};

  AlireviewApp.onKeypressText = function() {
    var el = $('[data-alireview-keypress="true"]');
    var target = el.attr("data-alireview-keypress-target");
    if ($(target).length) {
      var elTarget = document.querySelector(target);
      var elCount = elTarget.querySelector("span");
      var max = 200;
      el.addEventListener("keydown", function(e) {
        if (e.keyCode === 8) {
          var counting = el.value.length;
          if (counting <= max) {
            elCount.textContent = max - counting;
          }
        }
      });
      el.addEventListener("input", function(e) {
        var counting = el.value.length;
        if (counting <= max) {
          elCount.textContent = max - counting;
        } else {
          e.preventDefault();
        }
      });
    }
  };

  AlireviewApp.lightbox = function() {
    lightbox.load();
  };

  AlireviewApp.doSomeStuff = function() {
    var a = document.querySelectorAll(".alireview-review-image");
    if (a.length) {
      var aW = a[0].clientWidth;
      Array.prototype.forEach.call(a, function(item) {
        var aF = item.querySelector(".alireview-image-feature");
        var aChild = aF.querySelectorAll(".alireview-image-slide");
        var aWidth = aW * aChild.length + "px";
        aF.style.setProperty("width", aWidth);
        Array.prototype.forEach.call(aChild, function(x) {
          x.style.setProperty("width", aW + "px");
        });
      });
    }
  };

  AlireviewApp.init = function() {
    AlireviewApp.doSomeStuff();
    AlireviewApp.lightbox();
    AlireviewApp.onKeypressText();

    // if width <= 768px add class "alireview-column-xs"
    var shopifyAlireviewId = document.querySelector("#review-widget-box");
    if (shopifyAlireviewId) {
      var clientWidth = shopifyAlireviewId.clientWidth;
      if (clientWidth <= 768) {
        shopifyAlireviewId.classList.add("alireview-column-xs");
      }
    }
  };

  AlireviewApp.init();
  window.addEventListener("resize", function() {
    AlireviewApp.doSomeStuff();
    var shopifyAlireviewId = document.querySelector("#review-widget-box");
    if (shopifyAlireviewId) {
      var clientWidth = shopifyAlireviewId.clientWidth;
      if (clientWidth <= 768) {
        shopifyAlireviewId.classList.add("alireview-column-xs");
      } else {
        shopifyAlireviewId.classList.remove("alireview-column-xs");
      }
    }
  });
});

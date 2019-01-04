function insertExtensionId() {
  const script = document.createElement("script");
  const extId = chrome.runtime.id;
  script.innerHTML = `window.extensionId="${extId}"`;
  document.body.appendChild(script);
}

insertExtensionId();
$(function() {
  $(".extension-chrome-wrap").remove();
});

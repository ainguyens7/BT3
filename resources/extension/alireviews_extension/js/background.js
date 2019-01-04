// chrome.runtime.onConnectExternal.addListener(function(port) {
//   port.onMessage.addListener(function(request, sender) {
//     if (
//       request.hasOwnProperty("action") &&
//       request.action === "ALIREVIEWS_PING"
//     ) {
//       console.log("send back to sender");
//       port.postMessage({ response: "ALIREVIEWS_PONG" });
//       port.disconnect();
//     }
//   });
// });

chrome.runtime.onMessageExternal.addListener(function(request) {
  console.log(request);
});

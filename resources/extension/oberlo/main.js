import "bootstrap/js/dist/util";
import "bootstrap/js/dist/modal";
import "bootstrap/js/dist/alert";
import "bootstrap-multiselect";
import "./assets/styles/main.scss";

import {
  addAlireviewModalWrapper,
  addAlireviewWrapper
} from "./modules/helper";
import addAlireviewIcon from "./modules/addAlireviewIcon";
import registerListenerAlireviewIcon from "./modules/handlerAlireview";

import addToastWrapper from "./modules/addToast";

function bootstrapExtension() {
  addAlireviewWrapper();
  addAlireviewModalWrapper();
  addAlireviewIcon();
  addToastWrapper();
  registerListenerAlireviewIcon();
}

window.onload = _.debounce(function(event) {
  console.log(event);
  bootstrapExtension();
});

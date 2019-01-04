import view from "../views/toast.hbs";

export default function showToast(data, type = "warning") {
  const toastWrapper = document.querySelector(".alireview-toast-wrapper");
  toastWrapper.innerHTML = view({ message: data, type: type });
  $("#alireview-toast").alert();
  window.setTimeout(function() {
    $("#alireview-toast").alert("close");
  }, 7000);
  return;
}

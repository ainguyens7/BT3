import view from "../views/modalCheckAccountNotPermit.hbs";
export default function showModalNotPermit(data) {
  const alireviewModalWrapper = document.querySelector(
    ".alireview-modal-wrapper"
  );
  alireviewModalWrapper.innerHTML = view(data);
  $("#modalNotPermit").modal({ show: true });
}

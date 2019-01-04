// only localTranslate.blade
const elHeaderTitle = document.getElementById('realHeaderTitle');
const elHeaderDescript = document.getElementById('realHeaderDescript'); 
const elHeaderButtonWrite = document.getElementById('realHeaderButtonWrite'); 
const elName  = document.getElementById('realName'); 
const elNameNotice = document.getElementById('realNameNotice'); 
const elEmail = document.getElementById('realEmail'); 
const elEmailNotice = document.getElementById('realEmailNotice'); 
const elRating = document.getElementById('realRating'); 
const elRatingPlaceholder = document.getElementById('realRatingPlaceholder'); 
const elRatingPlaceholderNotice = document.getElementById('realRatingPlaceholderNotice'); 
const elFooterAddImg = document.getElementById('realFooterAddImg'); 
const elFooterSend = document.getElementById('realFooterSend'); 
const elButtonThank = document.getElementById('realButtonThank'); 

// input name
const inpHeaderTitle = document.querySelector('input[name="translate[title]"]');
const inpHeaderDescript = document.querySelector('input[name="translate[text_total_multi]"]');
const inpHeaderButtonWrite = document.querySelector('input[name="translate[button_add]"]');
const inpName = document.querySelector('input[name="translate[label_name]"]');
const inpNameNotice = document.querySelector('input[name="translate[error_required]"]');
const inpEmail = document.querySelector('input[name="translate[label_email]"]');
const inpEmailNotice = document.querySelector('input[name="translate[error_email]"]');
const inpRating = document.querySelector('input[name="translate[label_your_rating]"]');
const inpRatingPlaceholder = document.querySelector('input[name="translate[text_write_comment]"]');
const inpRatingPlaceholderNotice = document.querySelector('input[name="translate[error_rating_required]"]');
const inpFooterAddImg = document.querySelector('input[name="translate[label_image]"]');
const inpFooterSend = document.querySelector('input[name="translate[button_submit]"]');
const inpButtonThank = document.querySelector('input[name="translate[message_thanks_has_approve]"]');

inpHeaderTitle && inpHeaderTitle.addEventListener('keyup', (e) => elHeaderTitle && (elHeaderTitle.innerText = e.target.value));
inpHeaderDescript && inpHeaderDescript.addEventListener('keyup', (e) => elHeaderDescript && (elHeaderDescript.innerText = e.target.value));
inpHeaderButtonWrite && inpHeaderButtonWrite.addEventListener('keyup', (e) => elHeaderButtonWrite && (elHeaderButtonWrite.innerText = e.target.value));
inpName && inpName.addEventListener('keyup', (e) => elName && (elName.innerText = e.target.value));
inpNameNotice && inpNameNotice.addEventListener('keyup', (e) => elNameNotice && (elNameNotice.innerText = e.target.value));
inpEmail && inpEmail.addEventListener('keyup', (e) => elEmail && (elEmail.innerText = e.target.value));
inpEmailNotice && inpEmailNotice.addEventListener('keyup', (e) => elEmailNotice && (elEmailNotice.innerText = e.target.value));
inpRating && inpRating.addEventListener('keyup', (e) => elRating && (elRating.innerText = e.target.value));
inpRatingPlaceholder && inpRatingPlaceholder.addEventListener('keyup', (e) => elRatingPlaceholder && (elRatingPlaceholder.setAttribute('placeholder', e.target.value)));
inpRatingPlaceholderNotice && inpRatingPlaceholderNotice.addEventListener('keyup', (e) => elRatingPlaceholderNotice && (elRatingPlaceholderNotice.innerText = e.target.value));
inpFooterAddImg && inpFooterAddImg.addEventListener('keyup', (e) => elFooterAddImg && (elFooterAddImg.innerText = e.target.value));
inpFooterSend && inpFooterSend.addEventListener('keyup', (e) => elFooterSend && (elFooterSend.innerText = e.target.value));
inpButtonThank && inpButtonThank.addEventListener('keyup', (e) => elButtonThank && (elButtonThank.innerText = e.target.value));

// Reponsive use jQuery
$('#preview-real').on('shown.bs.modal', function () {
    const _wrapper = $('.wrapper-preview-real').parent().html();
    $('#showCurrentPreviewReal').html(_wrapper);
})


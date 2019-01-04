import config from './config';

$(document).on("click", ".btn-submit-review", function(e) {
  e.preventDefault();
  const self = $(this);

  // Validate 
  let countErrors = 0;
  const translate = JSON.parse( $('input[name="translate"]').val() );
  const template_error = `<label class="err"></label>`;
  $('[class*="alireview_error"]').remove();

  let inpAuthor = $('[name="alireview_author"]');
  if($(inpAuthor).val() === "") {
    let errAuthor = $(template_error).addClass('alireview_error_author');
    errAuthor = $(errAuthor).text(translate.error_name_required);
    $(errAuthor).insertAfter( $(inpAuthor) );
    countErrors++;
  }

  let inpContent = $('[name="alireview_content"]');
  if($(inpContent).val() === "") {
    let errContent = $(template_error).addClass('alireview_error_content');
    errContent = $(errContent).text(translate.error_rating_required);
    $(errContent).insertAfter( $(inpContent) );
    countErrors++;
  } 

  let inpEmail = $('[name="alireview_email"]');
  
  if( $(inpEmail).val() !== "" && !validateEmail( $(inpEmail).val() ) ) {
    let errEmail = $(template_error).addClass('alireview_error_email');
    errEmail = $(errEmail).text(translate.error_email);
    $(errEmail).insertAfter( $(inpEmail) );
    countErrors++;
  } 

  if(countErrors > 0) {
    return;
  }
  // END: Validate 

  // Animation loading
  $(self).find('.lds-dual-ring').remove();
  $(self).addClass("wrap-loading");
  $(self).prepend('<div class="lds-dual-ring"></div>');
  $(self).closest('form').find('input[type="text"], textarea').prop('readonly', true);
  $('.alireview_loading_upload').show();
  // END: Animation loading

  // When photo uploaded
  window.uploadPhoto.Upload().then(res => {
    $.ajax({
      method: "POST",
      url: config.shop_url + "/comment/post_review",
      data: $("#add_form_review").serialize(),
      success: function(e) {
        if (e.status == true) {
          
          // $("#add_form_review")
          //   .find(".err")
          //   .text("");
          // $("#add_form_review")
          //   .find("input[name=author]")
          //   .val("");
          // $("#add_form_review")
          //   .find("input[name=email]")
          //   .val("");
          // $("#add_form_review")
          //   .find("textarea")
          //   .val("");

          $(".reviewFormHead").html( `<div class="alireview-notice-addreview-success">${ e.message }</div>`);

          $(".alireview-btn-add-review").hide();

          // hide modal review
          if ($("#alireview-modal-add-review").length > 0) {
            var body = document.querySelector("body");
            var aliModal = document.querySelector(".alireview-modal");
            aliModal.classList.remove("alireview-modal-show");
            body.classList.remove("alireview-modal-open");
          }

          $("html,body").animate({
              scrollTop: absoluteOffset($("#shopify-ali-review").get(0)).top
          }, "slow");
        } else {
          $(self).removeClass("wrap-loading");
          $(self).find('.lds-dual-ring').remove();
          $(self).closest('form').find('input[type="text"], textarea').prop('readonly', false);
          $('.alireview_loading_upload').hide();

          // if (typeof e.err !== "undefined") {
          //   $("label.err").html("");
          //   var err = e.err;
            
          //   for (let k in err) {
          //     $("label.err-" + k).html(err[k]);
          //   }
          //   return false;
          // }

          // if (typeof e.error !== "undefined") {
          //   alert(e.error);
          //   return false;
          // }
        }
      },
      error: function() {
        $(self).removeClass("wrap-loading");
        $(self).find('.lds-dual-ring').remove();
      }
    }); // End ajax
  });
});

var alireview_run_like = 1;
$(document).on("click", ".alireview-comment-like", function(e) {
  e.preventDefault();
  var target = $(this);
  if (alireview_run_like == 1) {
    $.ajax({
      method: "POST",
      url: config.shop_url + "/comment/like",
      data: {
        comment_id: target.attr("data-comment_id"),
        shop_id: target.attr("data-shop_id")
      },
      beforeSend: function(rs) {
        alireview_run_like = 0;
        target.attr("disabled", true);
      },
      success: function(data) {
        alireview_run_like = 1;
        target.attr("disabled", false);
        if (data.status == "success") {
          target.addClass("active");
          $(".alireview-number-like-" + target.attr("data-comment_id")).text(
            data.data
          );

          if (data.type_like == "1") {
            target.addClass("active");
          } else {
            target.removeClass("active");
          }

          if (data.type_unlike == "-1") {
            $(
              ".alireview-number-unlike-" + target.attr("data-comment_id")
            ).text(data.dataUnlike);
            $(".alireview-number-unlike-" + target.attr("data-comment_id"))
              .closest("a")
              .removeClass("active");
          }
        } else {
          alert(data.message);
        }
      },
      error: function() {
        alireview_run_like = 1;
        target.attr("disabled", false);
        alert("An error, please try again.");
      }
    });
  }
});

var alireview_run_unlike = 1;
$(document).on("click", ".alireview-comment-unlike", function(e) {
  e.preventDefault();
  var target = $(this);
  if (alireview_run_unlike == 1) {
    $.ajax({
      method: "POST",
      url: config.shop_url + "/comment/unlike",
      data: {
        comment_id: target.attr("data-comment_id"),
        shop_id: target.attr("data-shop_id")
      },
      beforeSend: function(rs) {
        alireview_run_unlike = 0;
        target.attr("disabled", true);
      },
      success: function(data) {
        alireview_run_unlike = 1;
        target.attr("disabled", false);
        if (data.status == "success") {
          $(".alireview-number-unlike-" + target.attr("data-comment_id")).text(
            data.data
          );

          if (data.type_unlike == "1") {
            target.addClass("active");
          } else {
            target.removeClass("active");
          }

          if (data.type_like == "-1") {
            $(".alireview-number-like-" + target.attr("data-comment_id")).text(
              data.dataLike
            );
            $(".alireview-number-like-" + target.attr("data-comment_id"))
              .closest("a")
              .removeClass("active");
          }
        } else {
          alert(data.message);
        }
      },
      error: function() {
        alireview_run_unlike = 1;
        target.attr("disabled", false);
        alert("An error, please try again.");
      }
    });
  }
});

function absoluteOffset(element) {
  var top = 0,
    left = 0;
  do {
    top += element.offsetTop || 0;
    left += element.offsetLeft || 0;
    element = element.offsetParent;
  } while (element);

  return {
    top: top,
    left: left
  };
};

function validateEmail(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}
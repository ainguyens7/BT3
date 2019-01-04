document.addEventListener("DOMContentLoaded", function(event) {
  // Init
  select2(); // .select2
  multiSelectBootstrap(); // .multiselect
  multiSelectBootstrapSearch(); // .multiselect-search
  colorpicker(); // .colorpicker-init
  relTooltip(); // <span class="rel-tooltip" data-tooltip="<small>Hello world</small>" />
  select2ChooseIcon(); // .select2-view-icon
  toggleMenuMobile();
  tagsinput();
  tooltipBootstrap();
  datetimepicker(); // .datetimepicker
  initCreateMirror(); // .mirror-style
  var lightbox = new Lightbox();
  lightbox.load();

  // Function
  // upPhotos(); // modal_edit_review.blade
  aliSlideOwlStep(); // get_started.blade
  affiliateSettingsPage(); // seting.blade
  toggleIntercom();
  InstallPageValidateDomain();
  isOberloLoginCurrentShop();
  isAliordersLoginCurrentShop();
  // customDomainShopify(); // dashboard.blade

});

jQuery(document).ready(function($) {
  // add select 2 author thachleviet
  $(".nav-hamburger").on("click", function(ev) {
    $("body").toggleClass("open-nav-sidebar");
    $(".sidebar-wrap").css({ "z-index": "1041" });
    setTimeout(function(event) {
      $(".sidebar-wrap").removeAttr("style");
    }, 500);
  });

  $(".nav-sidebar-close").on("click", function(event) {
    $("body").removeClass("open-nav-sidebar");
    $(".sidebar-wrap").css({ "z-index": "1041" });
    setTimeout(function(event) {
      $(".sidebar-wrap").removeAttr("style");
    }, 500);
  });

  $(".chosen-single").chosen({
    width: "100%"
  });

  $(".warning-countrycode").tooltip({
    html: true,
    container: ".generate-add-reviews"
  });

  $(".select-multi").multiselect({
    nonSelectedText: "Choose Language",
    // max'Height': 222,
    // buttonWidth: '180px',
    buttonContainer: '<div class="selected-parents-container"></div>',
    enableFiltering: true,
    includeSelectAllOption: true,
    enableCaseInsensitiveFiltering: true
  });

  // $(".sidebar-container").mCustomScrollbar();

  // $(".youtube-link-wrap").mCustomScrollbar();

  $(".dropdown.keep-open").on({
    "shown.bs.dropdown": function() {
      $(this).attr("closable", false);
    },
    click: function() {}, // For some reason a click() is sent when Bootstrap tries and fails hide.bs.dropdown
    "hide.bs.dropdown": function() {
      return $(this).attr("closable") == "true";
    }
  });

  //== Modal Add Review
  //
  // $(".add-reviews-btn").on("click", function(ev) {
  //   ev.preventDefault();
  //   // $('#addReviewsModal').modal('show');
  // });

  $(".action-edit-btn").on("click", function(ev) {
    ev.preventDefault();
    $("#editReviewsModal").modal("show");
  });

  $(".action-delete-btn").on("click", function(ev) {
    ev.preventDefault();
    $("#deleteReviewsModal").modal("show");
  });

  // $(".review-approve-checked").on("change", function(ev) {
  //   var target = ev.currentTarget;
  //   if ($(target).is(":checked")) {
  //     $(target)
  //       .parents(".ars-table-row")
  //       .addClass("active");
  //   } else {
  //     $(target)
  //       .parents(".ars-table-row")
  //       .removeClass("active");
  //   }
  // });

  // $("#review-approve-select-all").on("change", function(ev) {
  //   var target = ev.currentTarget;
  //   if ($(target).is(":checked")) {
  //     $(".status-action-wrap").fadeIn('100');
  //     $('#slc_all_reviews_prod_wrap') && $('#slc_all_reviews_prod_wrap').fadeIn();
  //     $(".review-approve-checked").prop("checked", "checked");
  //     $(".review-approve-checked")
  //       .closest("tr")
  //       .addClass("row-selected");
  //   } else {
  //     $(".status-action-wrap").fadeOut();
  //     $('#slc_all_reviews_prod_wrap') && $('#slc_all_reviews_prod_wrap').fadeOut();
  //     $(".review-approve-checked").prop("checked", false);
  //     $(".review-approve-checked")
  //       .closest("tr")
  //       .removeClass("row-selected");
  //   }

  //   var totalChecked = document.querySelectorAll(
  //     "input.review-approve-checked:checked"
  //   ).length;
  //   $("#result-total-reviews-checked").html(
  //     '<div class="color-pink text-center d-inline-block" style="min-width: 25px;">' +
  //       totalChecked +
  //       "</div> of X review(s)"
  //   );
  // });


  // $(".review-approve-checked").on("change", function(ev) {
  //   if ($(this).is(":checked")) {
  //     $(this).prop("checked", "checked");
  //     $(this)
  //       .closest("tr")
  //       .addClass("row-selected");
  //   } else {
  //     $(this).prop("checked", false);
  //     $(this)
  //       .closest("tr")
  //       .removeClass("row-selected");
  //   }

  //   var totalChecked = document.querySelectorAll(
  //     "input.review-approve-checked:checked"
  //   ).length;
  //   $("#result-total-reviews-checked").html(
  //     'Total: <div class="color-pink text-center d-inline-block" style="min-width: 25px;">' +
  //       totalChecked +
  //       "</div> review(s)"
  //   );
  // });

  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    }
  });

  $("#switch-translate-language").on("change", function(ev) {
    var obj = $(this);
    $(".translate-customer-review").toggleClass("active");

    var status = 0;
    if (this.checked) {
      status = 1;
    }

    $.ajax({
      type: "post",
      url: appUrl + "/settings/status-local-translate",
      dataType: "json",
      data: {
        is_translate: status
      },
      beforeSend: function() {
        obj.attr("disabled", true).css("opacity", 0.5);
      },
      success: function(data, statusText, xhr) {
        obj.attr("disabled", false).css("opacity", 1);
        if (data.status && data.status == "success") {
          $.notify(
            {
              message: data.message
            },
            {
              z_index: 999999,
              timer: 2000,
              type: "success"
            }
          );
        } else {
          $.notify(
            {
              message: data.message
            },
            {
              z_index: 999999,
              timer: 2000,
              type: "danger"
            }
          );

          obj.click();
        }
      },
      error: function() {
        obj.attr("disabled", false);
        obj.click();
        $.notify(
          {
            message: lang_reviews.notice_error
          },
          {
            z_index: 999999,
            timer: 2000,
            type: "danger"
          }
        );
      }
    });
  });

  var text_fireapp = $(".support-btn").html();

  $(".support-btn").click(function(e) {
    var obj = $(this);
    var action = obj.attr("data-action");

    if (action == "show") {
      $(".fb-page").fadeIn();
      $(".fb-page").css("display", "block");
      obj
        .html("HIDE")
        .addClass("active")
        .attr("data-action", "hide");
    } else {
      obj
        .html(text_fireapp)
        .removeClass("active")
        .attr("data-action", "show");
      $(".fb-page").css("display", "none");
    }
  });

  // console.log(chrome.app.isInstalled);
  //console.log(chrome.webstore.install);
  // console.log(chrome);

  $(".btn-remove-data").click(function(e) {
    var obj = $(this);

    /*$("#deleteDataModal").modal({
          show: "true"
        });*/

    var modal = "#deleteDataModal";
    var plan = $(this).attr("data-plan");

    $(modal).on("shown.bs.modal", function() {
      $('input[name="plan"]', $(modal)).val(plan);
    });

    var originalModal = $(modal).clone();
    $(modal).on("hidden.bs.modal", function() {
      $(modal).remove();
      var myClone = originalModal.clone();
      $("body").append(myClone);
    });

    $(modal).modal({
      show: "true"
    });
  });

  $("body").delegate("#formDeleteData", "submit", function(e) {
    e.preventDefault();

    var obj = $(this);
    $.ajax({
      type: "post",
      url: appUrl + "/removeDataHandle",
      dataType: "json",
      data: {
        _token: $('input[name="_token"]', obj).val(),
        plan: $('input[name="plan"]', obj).val()
      },
      beforeSend: function() {
        obj.attr("disabled", true).css("opacity", 0.5);
      },
      success: function(data, statusText, xhr) {
        if (data.status && data.status == "success") {
          window.location.href = data.url;
        } else {
          obj.attr("disabled", false).css("opacity", 1);
          $.notify(
            {
              message: data.message
            },
            {
              z_index: 999999,
              timer: 2000,
              type: "danger"
            }
          );
        }
      },
      error: function() {
        obj.attr("disabled", false);
        $.notify(
          {
            message: lang_reviews.notice_error
          },
          {
            z_index: 999999,
            timer: 2000,
            type: "danger"
          }
        );
      }
    });
  });

  $(".review-app-btn").click(function(e) {});

  $(".discover-charge-thanks").click(function(e) {
    e.preventDefault();
    var link = $(this).attr("href");

    try {
      __adroll.record_user({ adroll_segments: "f9a182e3" });
    } catch (err) {}

    window.location.href = link;
  });

  $(".extension-content .close").click(function() {
    $(".extension-chrome-wrap").addClass("d-none");
  });

  $(".btn-vote-for-us").click(function(e) {
    var modal = "#voteForUsModal";
    $(modal).on("shown.bs.modal");

    var originalModal = $(modal).clone();
    $(modal).on("hidden.bs.modal", function() {
      $(modal).remove();
      var myClone = originalModal.clone();
      $("body").append(myClone);
    });

    $(modal).modal({
      show: "true"
    });
  });

  $("body").delegate(".btn-bad-review", "click", function(e) {
    var obj = $(this);
    var ctn = obj.closest("form");
    $(".step-1", ctn).hide();
    $(".step-2", ctn).fadeIn();
    ctn.submit();
  });

  $("body").delegate(".btn-good-review", "click", function(e) {
    e.preventDefault();

    window.open(
      "https://apps.shopify.com/ali-reviews?reveal_new_review=true",
      "_blank"
    );
    
    $("#voteForUsModal").modal("hide");

    $.ajax({
      type: "post",
      url: appUrl + "/review_app",
      dataType: "json",
      data: {},
      success: function(data, statusText, xhr) {
        console.log('Review app!');
      }
    });
  });

  $("body").delegate("#formVoteForUs", "submit", function(e) {
    e.preventDefault();
    var obj = $(this);
    obj.validate({
      rules: {
        feedback: "required"
      },
      submitHandler: function(form) {
        $.ajax({
          type: "post",
          url: appUrl + "/feedback/save",
          dataType: "json",
          data: $(form).serialize(),
          beforeSend: function() {
            $("input, button, textarea", $(form))
              .attr("disabled", true)
              .css("opacity", 0.5);
          },
          success: function(data, statusText, xhr) {
            if (data.status == "success") {
              //alert(data.message);
              $.notify(
                {
                  message: data.message
                },
                {
                  z_index: 999999,
                  timer: 2000,
                  type: "success"
                }
              );

              //window.location.reload();
              $("#voteForUsModal").modal("hide");
              $("#modalImportReview").modal("hide");
            } else {
              $("input, button, textarea", $(form))
                .attr("disabled", false)
                .css("opacity", 1);
              $.notify(
                {
                  message: data.message
                },
                {
                  z_index: 999999,
                  timer: 2000,
                  type: "danger"
                }
              );
            }
          },
          error: function() {
            $("input, button, textarea", $(form))
              .attr("disabled", false)
              .css("opacity", 1);
            //alert(lang_reviews.notice_error);
            $.notify(
              {
                message: lang_reviews.notice_error
              },
              {
                z_index: 999999,
                timer: 2000,
                type: "danger"
              }
            );
          }
        });
      }
    });
  });

  $(".btn-invite-friend").click(function(e) {
    e.preventDefault();
    var obj = $(this);

    var modal = "#inviteFriendModal";
    // var plan = $(this).attr('data-plan');

    $(modal).on("shown.bs.modal", function() {
      //$('input[name="plan"]',$(modal)).val(plan);
    });

    var originalModal = $(modal).clone();
    $(modal).on("hidden.bs.modal", function() {
      $(modal).remove();
      var myClone = originalModal.clone();
      $("body").append(myClone);
    });

    $(modal).modal({
      show: "true"
    });
  });

  $("#form-create-reviews-page").validate({
    rules: {
      title: "required"
    },
    submitHandler: function(form) {
      $.ajax({
        type: "post",
        url: appUrl + "/create-reviews-page",
        dataType: "json",
        data: $(form).serialize(),
        beforeSend: function() {
          $("input, button, textarea", $(form))
            .attr("disabled", true)
            .css("opacity", 0.5);
        },
        success: function(data, statusText, xhr) {
          $("input, button, textarea", $(form))
            .attr("disabled", false)
            .css("opacity", 1);
          if (data.status == "success") {
            //alert(data.message);
            $.notify(
              {
                message: data.message
              },
              {
                z_index: 999999,
                timer: 2000,
                type: "success"
              }
            );
            window.location.reload();
          } else {
            $.notify(
              {
                message: data.message
              },
              {
                z_index: 999999,
                timer: 2000,
                type: "danger"
              }
            );
          }
        },
        error: function() {
          $("input, button, textarea", $(form))
            .attr("disabled", false)
            .css("opacity", 1);
          //alert(lang_reviews.notice_error);
          $.notify(
            {
              message: lang_reviews.notice_error
            },
            {
              z_index: 999999,
              timer: 2000,
              type: "danger"
            }
          );
        }
      });
    }
  });
});

function aliSlideOwlStep() {
    var _json_data = [
        {
            "title": "Get Install Now",
            "sub_title": "Step 1",
            "description": "Click <strong> “INSTALL NOW” </strong> to get Extension on Chrome !"
        },
        {
            "title": "Ervin Howell",
            "sub_title": "Step 2",
            "description": "Search for the product then click  <strong> “ADD REVIEWS“ <strong> "
        },
        {
            "title": "Antonette",
            "sub_title": "Step 3",
            "description": "Enter the AliExpress.com URL of the product and click <strong> “NEXT” <strong>."
        },
        {
            "title": "Clementine Bauch",
            "sub_title": "Step 4",
            "description": "Select options to filter which reviews you want to show."
        },
        {
            "title": "Check results ",
            "sub_title": "Step 5",
            "description": "Wait a few seconds and check the result."
        }
    ];
    var owl = $('.owl-carousel');
    owl.on('initialized.owl.carousel', function (event) {
        var _count_item = event.item.count;
        var _elWrap = $('.ali-owl-btn');
        for (var i = 0; i < _count_item; i++) {
            if (i == 0)
                $(_elWrap).append("<button class=\"button button--default active\" owl-index=\"" + i + "\">" + (i + 1) + "</button>");
            else
                $(_elWrap).append("<button class=\"button button--default \" owl-index=\"" + i + "\">" + (i + 1) + "</button>");
        }
        $('.ali-owl-btn button').on('click', function (ele) {
            var _btn_index = $(this).attr('owl-index');
            owl.trigger("to.owl.carousel", [_btn_index, 300, true]);
        });
    });
    owl.owlCarousel({
        items: 1,
        margin: 20,
        nav: true,
        dots: true,
        loop: true,
        autoplay: false,
        autoplayTimeout: 5000,
        autoWidth: false,
        navText: ['<span class="material-icons">keyboard_arrow_left</span>', '<span class="material-icons">keyboard_arrow_right</span>']
    });

    owl.on('changed.owl.carousel', function (event) {
        var _owl_index = event.page.index;
        var _buttons = $('.ali-owl-btn').find('button');
        var _data = _json_data[_owl_index];
        $('.ali-owl--title').html(_data.title);
        $('.ali-owl--sub-title').html(_data.sub_title);
        $('.ali-owl--description').html(_data.description);
        _buttons.each(function (index, el) {
            $(el).removeClass('active');
            $(_buttons[_owl_index]).addClass('active');
        });
    })
}

function select2() {
  $(".select2:not('.unsearch')").select2();

  $(".select2.unsearch").select2({
    minimumResultsForSearch: Infinity
  });

  // Custom 
  $('.selectActionAllReviews2').select2({
    placeholder: "Action",
    minimumResultsForSearch: Infinity
  });

  $('#selectActionAllReviews').select2({
    placeholder: "Action",
    minimumResultsForSearch: Infinity
  });
}

function toggleIntercom() {
  let isDisplayIntercom = false;
  $('.alrv-toggle-intercom') && $('.alrv-toggle-intercom').on('click', function() {
    var intercom_on_messager = document.querySelector('.intercom-messenger-frame');
      if( intercom_on_messager == null ) {
          Intercom('show');
      } else {
          Intercom('hide');
      }
  });
}
// upPhotos();
function upPhotos() {
  $(".editReview").on("click", function() {
    $("#editReviewsModal").on("show.bs.modal", function(e) {
      //Check File API support
      if (window.File && window.FileList && window.FileReader) {
        $(".up-photos-input").on("change", function(event) {
          $(".wrapper-up-photo__item").remove();

          var files = event.target.files; //FileList object
          var output = $(".result");

          for (var i = 0; i < files.length; i++) {
            var file = files[i];

            if (!file.type.match("image")) continue;

            var picReader = new FileReader();
            picReader.addEventListener("load", function(event) {
              var picFile = event.target;
              var div = document.createElement("div");
              div.classList = "wrapper-up-photo__item d-inline-block";
              div.innerHTML =
                "<img src='" +
                picFile.result +
                "' class=\"img-rounded preview-result\" title='" +
                picFile.name +
                "' />";

              output.prepend(div);
            });

            picReader.readAsDataURL(file);
          }
        });
      } else {
        console.log("Your browser does not support File API");
      }
    });
  });
}

function multiSelectBootstrap(nonText) {
  $('.multiselect').multiselect({
    nonSelectedText: nonText || 'All Stars',
    templates: { 
      li: '<li class="checkList"><label class="wrap-custom-box"> one<input type="checkbox" checked="checked"><span class="checkmark-ckb"></span></label></li>',
    },
    onInitialized: function(select, container) {
      $('.multiselect').css({
        'opacity': '1'
      });
    }
  });

  $('.multiselect + div .multiselect-container .wrap-custom-box').each(function (index) {
    $(this).append('<span class="checkmark-ckb"></span>');

    $(this).click(function (e) {
        e.stopPropagation();
    });
  });
}

function multiSelectBootstrapSearch() {
  $('.multiselect-search').multiselect({
    templates: { 
      li: '<li class="checkList"><label class="wrap-custom-box"> one<input type="checkbox" checked="checked"><span class="checkmark-ckb"></span></label></li>',
      filter: '<li class="multiselect-item filter"><div class="input-group" style="width: 100%; padding-right: 10px;"><input class="form-control multiselect-search" type="text"></div></li>',
      filterClearBtn: false,
    },
    enableFiltering: true,
    includeSelectAllOption: true,
    enableCaseInsensitiveFiltering: true,
    onInitialized: function(select, container) {
      $('.multiselect, .multiselect-search').css('opacity', '1');
    }
  });

  $('.multiselect-search + div .multiselect-container .wrap-custom-box').each(function (index) {
    $(this).append('<span class="checkmark-ckb"></span>');

    $(this).click(function (e) {
        e.stopPropagation();
    });
  });
}

function colorpicker() {
  $('.colorpicker-init').colorpicker();
}

function relTooltip() {
  $('.rel-tooltip').each(function(index, value) {
    const dataTooltip = $(this).attr('data-tooltip');
    const placeTooltip = $(this).attr('place-tooltip') || 'right';
    if(dataTooltip) {
      const html = `
        <div class="tooltip fade ${placeTooltip}">
            <div class="tooltip-arrow"></div>
            <div class="tooltip-inner">${dataTooltip}</div>
        </div>
        `;
      $(this).append(html);
    }
  });
}

function affiliateSettingsPage() {
  $('input[name="setting[affiliate_program]"]').on('change', function() {
    $('.aff-block').removeClass('in');
    switch($(this).val()) {
      case 'aliexpress':
          // code block
          $(this).closest('.aff-block').addClass('in');
          break;
      case 'admitad':
          // code block
          $(this).closest('.aff-block').addClass('in');
          break;
      default:
          // code block
    }
  })
}

function formatState (state) {
  if(state.element) {
    var $state = $('<i class="' + $(state.element.outerHTML).attr('icon') + '" >' + state.text + '</i>');
  }
  return $state;
};

function select2ChooseIcon() {
  $(".select2-view-icon").select2({
    templateResult: formatState,
    minimumResultsForSearch: Infinity,
    templateSelection: formatState,
    theme: "default select-theme-setting"
  });


  $(".select2-view-icon").on('select2:open', function() {
    var ariaOwns = $('.select2-view-icon + span.select2 .select2-selection').attr('aria-owns');
    $('#' + ariaOwns).addClass('select2-view-icon');
  })

  $(".select2-view-icon").on('change', function() {
    var ele = $(this).next().find('.select2-selection').attr('aria-owns');
    var icon = '<i class="'+$(this).find(':selected').attr('icon')+'" style="color: #FFB303;"></i>'
    $('[aria-owns="' + ele + '"]').find('.select2-selection__rendered').html(icon);
  })
}

function toggleMenuMobile() {
	$('.sidebar-toggle').on('click', function() {
		var _data_toggle = $(this).attr('data-toggle');
		if(_data_toggle == "push-menu") {
			$('body').toggleClass('open-menu open-menu--push');
		} 
		else if( _data_toggle == "over-menu") {
			
			$('.blind-wall-menu').toggleClass('open');
		}
	});
}

function tagsinput() {
  $('.tagsinput') && $('.tagsinput').tagsInput({
    defaultText: 'keywords'
  });
}

function tooltipBootstrap() {
  $('[data-toggle="tooltip"]').tooltip({
    html: true,
    container: "body"
  });
}

function datetimepicker() {
  $('.datetimepicker').datetimepicker();
}

function addLoading(el) {
  $(el).addClass('wrap-loading');
  $(el).prepend('<div class="lds-dual-ring"></div>');
}

function removeLoading(el) {
  $(el).removeClass('wrap-loading');
  $(el).find('.lds-dual-ring').remove();
}

function hiddenCustomName(name) {
	var res = name.split("");
  for(i = 1; i < res.length; i++) {
    res[i] = res[i] == ' ' ? res[i] : '*';
  }
  return res.join('');
}

function displayNotify(msg, style) {
  $.notify(
    {
      message: msg
    },
    {
      z_index: 999999,
      timer: 2000,
      type: style
    }
  );
}

function initCreateMirror() {
  const mirrorCode = document.getElementsByClassName('mirror-code');
  for(let i = 0; i < mirrorCode.length; i++) {
    CodeMirror.fromTextArea(mirrorCode[i], {
      lineNumbers: true,
      mode: "javascript",
      tabSize: 2
    });
  }
}

function customDomainShopify() {
  const inputForm = document.querySelector('form[name="installShopify"] input[type="text"]');
  const lblErrForm = document.querySelector('.welcome-page .error');
  inputForm && inputForm.addEventListener('keyup', function() {
    if(this.value && this.value.indexOf('.myshopify.com') > -1) {
      this.value = getDomainShopify(this.value);
      lblErrForm.innerText = '';
    } else {
      lblErrForm.innerText = "Shop doesn't exist. Please check your URL & try again";
    }
  });
}

function getDomainShopify(url) {
  // only use: customDomainShopify()
  const httpsw = url;
  const domain = ".myshopify.com";
  const arrDomainShopify = httpsw.split(domain);
  return arrDomainShopify[0].replace(/^(?:https?:\/\/)?(?:www\.)?/i, "").split('/')[0] + domain;
}

function validateDomainShop(_domain) {
  var pattHTTP_HTTPS = /^(https?:\/\/)/gi;
  var pattMyShopify = /(.myshopify.com(\/|\/admin)?)$/gi;
  var pattNotSpecial = /[^a-zA-Z0-9-_]/gi;

  if(_domain.match(pattHTTP_HTTPS)) {
    _domain = _domain.split("//")[1];
  } 

  if(_domain.match(pattMyShopify)) {
    _domain = _domain.split(".myshopify.com")[0];
    
    if( !_domain.match(pattNotSpecial) ) {
      return true;
    }
  }

  return false;
}

function InstallPageValidate() {
  const pattHTTP_HTTPS = /^(https?:\/\/)/gi; 
  let domain = $('.welcome-page input[name="shop"]');
  let domain_val = $(domain).val();

  if( validateDomainShop(domain_val) && domain_val !== '' ) {
    // true
    let new_domain = domain_val.match(pattHTTP_HTTPS) ? domain_val.split("//")[1] : domain_val;
    new_domain = new_domain.replace('_', '-');
    new_domain = new_domain.replace('.myshopify.com/admin', '.myshopify.com');
    new_domain = new_domain.replace('.myshopify.com/', '.myshopify.com');
    
    $(domain).val(new_domain);
    $('form[name="installShopify"]')[0].submit();
  } else {
    // false
    $('#shop-error').text('Please type your myshopify link (store_name.myshopify.com)');
  }
}

function InstallPageValidateDomain() {
  // Click button submit
  $('form[name="installShopify"] .buttn__submit').on('click', function(e) {
    InstallPageValidate();
  });

  // Enter submit
  $('form[name="installShopify"]').submit(function(e) {
    e.preventDefault();
    InstallPageValidate();
  });
}

/** 
 * Sidebar "Import from Oberlo"
 * Kiểm tra Backend và Oberlo có sử dụng chung SHOP không
*/
let isProcessCheckOberloLogin = false;
function isOberloLoginCurrentShop() {
  $('#isOberloLoginCurrentShop').on('click', function() {
    if(isProcessCheckOberloLogin == false) {
      const infoShop = $('#info_shop');

      if($(infoShop).length != 0) {
        isProcessCheckOberloLogin = true;

        window.postMessage({
            type_message: "isChekOberloLoginCurrentShop",
            shop: $(infoShop).attr('shop')
          },
          "*"
        );
      }
    }
  });
}

function isChekOberloLoginCurrentShop_result(isSameShopdomain, urlOberloProduct) {
  isProcessCheckOberloLogin = false;
  
  if(isSameShopdomain == true) {
    window.open(urlOberloProduct, '_blank');
  } else {
    $.notify(
      {
        message: `<div style="white-space: nowrap;">Please login your store to <a href="https://${ $('#info_shop').attr('shop') }/admin/apps" target="_blank">Oberlo</a></div>`
      },
      {
        z_index: 999999,
        timer: 2000,
        type: "warning"
      }
    );
  }
}

/** 
 * Sidebar "Import from Aliorder"
 * Kiểm tra Backend và Aliorder có sử dụng chung SHOP không
*/
let isProcessCheckAliordersLogin = false;
function isAliordersLoginCurrentShop() {
  $('#isAliordersLoginCurrentShop').on('click', function() {
    if(isProcessCheckAliordersLogin == false) {
      const infoShop = $('#info_shop');

      if($(infoShop).length != 0) {
        isProcessCheckAliordersLogin = true;

        window.postMessage({
            type_message: "isChekAliordersLoginCurrentShop",
            shop: $(infoShop).attr('shop')
          },
          "*"
        );
      }
    }
  });
}

function isChekAliordersLoginCurrentShop_result(isSameShopdomain, urlAliordersProduct) {
  isProcessCheckAliordersLogin = false;
  
  if(isSameShopdomain == true) {
    window.open(urlAliordersProduct, '_blank');
  } else {
    $.notify(
      {
        message: `<div style="white-space: nowrap;">Please login your store to <a href="https://${ $('#info_shop').attr('shop') }/admin/apps" target="_blank">Ali Orders</a></div>`
      },
      {
        z_index: 999999,
        timer: 2000,
        type: "warning"
      }
    );
  }
}

window.addEventListener("message", event => {
  if (event.source != window) return;
  const {
    data: { 
      type_message = "",
      isSameShopdomain = "",
      urlOberloProduct = "",
      urlAliordersProduct = ""
    }
  } = event;

  type_message == "isChekOberloLoginCurrentShop_result" && isChekOberloLoginCurrentShop_result(isSameShopdomain, urlOberloProduct);
  type_message == "isChekAliordersLoginCurrentShop_result" && isChekAliordersLoginCurrentShop_result(isSameShopdomain, urlAliordersProduct);
},
false
);
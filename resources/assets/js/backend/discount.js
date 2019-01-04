jQuery(document).ready(function($) {
  $(".btn-add-discount-code").click(function(e) {
    e.preventDefault();
    var obj = $(this);

    var plan = obj.attr("data-plan");
    var modal = "#modalDiscountHandle";
    $(modal).on("shown.bs.modal", function() {
      $.ajax({
        type: "get",
        url: appUrl + "/planInfo",
        dataType: "json",
        data: {
          plan: plan
        },
        beforeSend: function() {
          obj.attr("disabled", true).css("opacity", 0.5);
        },
        success: function(data, statusText, xhr) {
          obj.attr("disabled", false).css("opacity", 1);
          if (data.status == "success") {
            var planInfo = data.data;

            $('input[name="app_plan"]', $(modal)).val(planInfo.name);
            $(".package_name", $(modal)).text(planInfo.package_name);
            $(".current_discount", $(modal)).text(planInfo.current_discount);
            $(".total_payment", $(modal)).text(planInfo.total_payment);
            $(".price", $(modal)).text(planInfo.price);
            switch (planInfo.name) {
              case "premium":
                $("button[type='submit']").attr("id", "button-purchase-pro");
                break;
              case "unlimited":
                $("button[type='submit']").attr("id", "button-purchase-unlimited");
                break;
              default:
                break;
            }
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

            $(modal).modal("hide");
          }
        },
        error: function() {
          obj.attr("disabled", false).css("opacity", 1);
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
          $(modal).modal("hide");
        }
      });
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

  $("body").delegate("#formDiscountHandle", "submit", function(e) {
    e.preventDefault();
    var obj = $(this);

    var discount_code = $('input[name="discount_code"]', obj).val();
    if (discount_code == "") {
      $('.message-code-discount').text('Please enter your discount code!!')
    } else {
      $.ajax({
        type: "post",
        url: appUrl + "/addDiscount",
        dataType: "json",
        data: obj.serialize(),
        beforeSend: function() {
          $("input, button, textarea,a", obj)
            .attr("disabled", true)
            .css("opacity", 0.5);
        },
        success: function(data, statusText, xhr) {
          $("input, button, textarea,a", obj)
            .attr("disabled", false)
            .css("opacity", 1);
          if (data.status == "success") {
            window.location.href = data.url;
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
          $("input, button, textarea,a", obj)
            .attr("disabled", false)
            .css("opacity", 1);
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

  function debounce(func, wait, immediate) {
    var timeout;
    return function() {
      var context = this,
        args = arguments;
      var later = function() {
        timeout = null;
        if (!immediate) func.apply(context, args);
      };
      var callNow = immediate && !timeout;
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
      if (callNow) func.apply(context, args);
    };
  }
  $("body").delegate(
    "#discount_code",
    "keyup",
    debounce(function(e) {
      e.preventDefault();
      var obj = $(this).closest("form");
      var discount_code = $('input[name="discount_code"]', obj).val();
      var app_plan = $('input[name="app_plan"]', obj).val();

      $.ajax({
        type: "get",
        url: appUrl + "/checkDiscount/" + discount_code,
        dataType: "json",
        data: {
            app_plan : app_plan
        },
        beforeSend: function() {
          $("button, textarea,a", obj)
            .attr("disabled", true)
            .css("opacity", 0.5);
        },
        success: function(data, statusText, xhr) {
          $("button, textarea,a", obj)
            .attr("disabled", false)
            .css("opacity", 1);
          if (data.status == "success") {
            /*$.notify({
                        message: data.message
                    },{
                        z_index: 999999,
                        timer: 2000,
                        type: 'success'
                    });*/

            $(".message-code-discount")
              .text(data.message)
              .removeClass("text-danger")
              .addClass("text-success");
          } else {
            /*$.notify({
                        message: data.message
                    },{
                        z_index: 999999,
                        timer: 2000,
                        type: 'danger'
                    });*/
            $("button[type='submit']",obj).attr("disabled", true);
            $(".message-code-discount")
              .text(data.message)
              .addClass("text-danger")
              .removeClass("text-success");
          }
        },
        error: function() {
          $("button, textarea,a", obj)
            .attr("disabled", false)
            .css("opacity", 1);
          $(".message-code-discount")
            .text(data.message)
            .addClass("text-danger")
            .removeClass("text-success");
        }
      });
    }, 300)
  );
});

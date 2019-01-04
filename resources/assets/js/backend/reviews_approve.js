jQuery(document).ready(function($) {
  $(".deleteReview").click(function(e) {
    e.preventDefault();

    var modal = "#deleteReviewsModal";
    var comment_id = $(this).attr("data-comment_id");

    $(modal).on("shown.bs.modal", function() {
      var obj = $(this);
      $('input[name="comment_id"]', obj).val(comment_id);
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

  // $("body").delegate("#formDeleteReview", "submit", function(e) {
  //   e.preventDefault();
  //   var obj = $(this);

  //   var disabled = obj.attr("disabled");
  //   if (disabled) {
  //     return false;
  //   }

  //   $.ajax({
  //     type: "post",
  //     url: appUrl + "/review/delete",
  //     dataType: "json",
  //     data: obj.serialize(),
  //     beforeSend: function() {
  //       $("input, button, textarea", obj)
  //         .attr("disabled", true)
  //         .css("opacity", 0.5);
  //     },
  //     success: function(data, statusText, xhr) {
  //       if (data.status && data.status == "success") {
  //         var comment_id = $('input[name="comment_id"]', obj).val();
  //         $("#ars-table-row-" + comment_id).remove();

  //         $.notify(
  //           {
  //             message: data.message
  //           },
  //           {
  //             z_index: 999999,
  //             timer: 2000,
  //             type: "success"
  //           }
  //         );

  //         $("#deleteReviewsModal").modal("hide");
  //       } else {
  //         $.notify(
  //           {
  //             message: data.message
  //           },
  //           {
  //             z_index: 999999,
  //             timer: 2000,
  //             type: "danger"
  //           }
  //         );

  //         $("input, button, textarea", obj)
  //           .attr("disabled", false)
  //           .css("opacity", 1);
  //       }
  //     },
  //     error: function() {
  //       $("input, button, textarea", obj)
  //         .attr("disabled", false)
  //         .css("opacity", 1);

  //       $.notify(
  //         {
  //           message: lang_reviews.notice_error
  //         },
  //         {
  //           z_index: 999999,
  //           timer: 2000,
  //           type: "danger"
  //         }
  //       );
  //     }
  //   });
  // });

  $(".approveReview").click(function(e) {
    e.preventDefault();
    var obj = $(this);

    var comment_id = obj.attr("data-comment_id");
    var type = obj.attr("data-type");
    var _token = obj.attr("data-token");

    $.ajax({
      type: "post",
      url: appUrl + "/review/approve",
      dataType: "json",
      data: {
        comment_id: comment_id,
        type: type,
        _token: _token
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

          window.setTimeout(function() {
            window.location.reload();
          }, 2000);

          $("#deleteReviewsModal").modal("hide");
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
        $("input, button, textarea", obj)
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
  });

  // $(".review-approve-checked").on("change", function(ev) {
  //   var target = ev.currentTarget;
  //   if ($(target).is(":checked")) {
  //     $(".status-action-wrap").fadeIn();
  //   } else {
  //     var listReviewCheck = $(
  //       'input[name="listReviewCheck[]"]'
  //     ).serializeArray();
  //     console.log(listReviewCheck);
  //     if (listReviewCheck.length <= 0) {
  //       $("#review-approve-select-all").prop("checked", false);
  //       $(".status-action-wrap").fadeOut();
  //     }
  //   }
  // });

  // $(".approve-all-btn").click(function(e) {
  //   e.preventDefault();
  //   var obj = $(this);
  //   var _token = obj.attr("data-token");
  //   var action = "publish";
  //   var listReviewCheck = $('input[name="listReviewCheck[]"]').serializeArray();

  //   const _listReviewCheck = listReviewCheck.map(item => {
  //     const value = item["value"];
  //     const el = $(`#review-${value}`);
  //     const source = el ? el.attr("data-source") : "";
  //     const product_id = el ? el.attr("data-product-id") : "";
  //     return { name: item["name"], value, source, product_id };
  //   });

  //   $.ajax({
  //     type: "post",
  //     url: appUrl + "/review/multi-action",
  //     dataType: "json",
  //     data: {
  //       action: action,
  //       type: "approve",
  //       _token: _token,
  //       listReviewCheck: _listReviewCheck
  //     },
  //     beforeSend: function() {
  //       obj.attr("disabled", true).css("opacity", 0.5);
  //     },
  //     success: function(data, statusText, xhr) {
  //       if (data.status && data.status == "success") {
  //         $.notify(
  //           {
  //             message: data.message
  //           },
  //           {
  //             z_index: 999999,
  //             timer: 2000,
  //             type: "success"
  //           }
  //         );

  //         window.location.reload();
  //       } else {
  //         obj.attr("disabled", false).css("opacity", 1);

  //         $.notify(
  //           {
  //             message: data.message
  //           },
  //           {
  //             z_index: 999999,
  //             timer: 2000,
  //             type: "danger"
  //           }
  //         );
  //       }
  //     },
  //     error: function() {
  //       obj.attr("disabled", false).css("opacity", 1);

  //       $.notify(
  //         {
  //           message: lang_reviews.notice_error
  //         },
  //         {
  //           z_index: 999999,
  //           timer: 2000,
  //           type: "danger"
  //         }
  //       );
  //     }
  //   });
  // });

  function pendingReviewsApprove(dataToken) {
    var _token = dataToken;
    var action = "publish";
    var listReviewCheck = $('input[name="listReviewCheck[]"]').serializeArray();

    const _listReviewCheck = listReviewCheck.map(item => {
      const value = item["value"];
      const el = $(`#review-${value}`);
      const source = el ? el.attr("data-source") : "";
      const product_id = el ? el.attr("data-product-id") : "";
      return { name: item["name"], value, source, product_id };
    });
    $.ajax({
      type: "post",
      url: appUrl + "/review/multi-action",
      dataType: "json",
      data: {
        action: action,
        type: "approve",
        isAllReviews: $('#ckc-all-reviews-prod').is(':checked'),
        uncheckCurrentPage: $('#product-uncheck-current-page').val(),
        _token: _token,
        listReviewCheck: _listReviewCheck
      },
      beforeSend: function() {
        $(".pending-reviews-action + span").css({
          opacity: "0.6",
          "pointer-events": "none"
        });
      },
      success: function(data, statusText, xhr) {
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

        $(".pending-reviews-action + span").css({
          opacity: "1",
          "pointer-events": "initial"
        });
        $(".pending-reviews-action")
          .val("")
          .trigger("change");
      },
      error: function() {
        console.log(3);
        $(".pending-reviews-action + span").css({
          opacity: "1",
          "pointer-events": "initial"
        });

        $(".pending-reviews-action")
          .val("")
          .trigger("change");

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

  // $(".remove-all-btn").click(function(e) {
  //   e.preventDefault();
  //   $("#deleteMulTiReviewsModal").modal({
  //     show: "true"
  //   });
  // });

  // $(".hide-all-btn").click(function(e) {
  //   e.preventDefault();
  //   $("#hideAllReviewsModal").modal({
  //     show: "true"
  //   });
  // });

  $(".pending-reviews-action").on("change", function() {
    const action = $(this).val();
    const data_token = $(this).attr("data-token");
    switch (action) {
      case "publish":
        console.log('PENDING PAGE [1/] action Approve All');
        pendingReviewsApprove(data_token);
        break;
      case "unpublish":
        console.log('PENDING PAGE [2/] action Remove All');
        $("#deleteMulTiReviewsModal").modal({
          show: "true"
        });
        $(this)
          .val("")
          .trigger("change");
        break;
      default:
        console.log("Completed!");
    }
  });

  // $("body").delegate("#formDeleteMultiReview", "submit", function(e) {
  //   e.preventDefault();
  //   var listReviewCheck = $('input[name="listReviewCheck[]"]').serializeArray();
  //   const _listReviewCheck = listReviewCheck.map(item => {
  //     const value = item["value"];
  //     const el = $(`#review-${value}`);
  //     const source = el ? el.attr("data-source") : "";
  //     const product_id = el ? el.attr("data-product-id") : "";
  //     return { name: item["name"], value, source, product_id };
  //   });
  //   var obj = $(this);
  //   $.ajax({
  //     type: "post",
  //     url: appUrl + "/review/multi-action",
  //     dataType: "json",
  //     data: {
  //       action: "delete",
  //       _token: $('input[name="_token"]', obj).val(),
  //       listReviewCheck: _listReviewCheck
  //     },
  //     beforeSend: function() {
  //       obj.attr("disabled", true).css("opacity", 0.5);
  //     },
  //     success: function(data, statusText, xhr) {
  //       if (data.status && data.status == "success") {
  //         $.notify(
  //           {
  //             message: data.message
  //           },
  //           {
  //             z_index: 999999,
  //             timer: 2000,
  //             type: "success"
  //           }
  //         );

  //         window.location.reload();
  //       } else {
  //         obj.attr("disabled", false).css("opacity", 1);
  //         $.notify(
  //           {
  //             message: data.message
  //           },
  //           {
  //             z_index: 999999,
  //             timer: 2000,
  //             type: "danger"
  //           }
  //         );
  //       }
  //     },
  //     error: function() {
  //       obj.attr("disabled", false);
  //       $.notify(
  //         {
  //           message: lang_reviews.notice_error
  //         },
  //         {
  //           z_index: 999999,
  //           timer: 2000,
  //           type: "danger"
  //         }
  //       );
  //     }
  //   });
  // });

  $("body").delegate("#formHideAllReview", "submit", function(e) {
    e.preventDefault();
    var listReviewCheck = $('input[name="listReviewCheck[]"]').serializeArray();
    var obj = $(this);
    $.ajax({
      type: "post",
      url: appUrl + "/review/multi-action",
      dataType: "json",
      data: {
        action: "unpublish",
        _token: $('input[name="_token"]', obj).val(),
        listReviewCheck: listReviewCheck
      },
      beforeSend: function() {
        obj.attr("disabled", true).css("opacity", 0.5);
      },
      success: function(data, statusText, xhr) {
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

          window.location.reload();
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

  $(".select-showing-reviews").change(function() {
    var obj = $(this);
    var perPage = obj.val();

    window.location.href = "?perPage=" + perPage;
  });

  $(".hideReview").click(function(e) {
    e.preventDefault();
    var obj = $(this);

    var comment_id = obj.attr("data-comment_id");
    var _token = obj.attr("data-token");

    $.ajax({
      type: "post",
      url: appUrl + "/review/change-status",
      dataType: "json",
      data: {
        comment_id: comment_id,
        status: 0,
        _token: _token
      },
      beforeSend: function() {
        obj.attr("disabled", true).css("opacity", 0.5);
      },
      success: function(data, statusText, xhr) {
        obj.attr("disabled", false).css("opacity", 1);

        if (data.status && data.status == "success") {
          var comment_id = $('input[name="comment_id"]', obj).val();
          obj.closest(".ars-table-row").remove();

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
        }
      },
      error: function() {
        obj.attr("disabled", true).css("opacity", 0.5);
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
});

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
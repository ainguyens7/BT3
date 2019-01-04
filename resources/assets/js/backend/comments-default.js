jQuery(document).ready(function($) {
  $(".add-default-review-btn").click(function(e) {
    e.preventDefault();
    var modal = "#modalSaveCommentDefault";

    $(modal).on("shown.bs.modal", function() {
      var obj = $(this);
      $(".modal-title", obj).text("Add new comment default");
      //$('input[name="name"]',obj).val(category_id);
      $("form", obj).submit();
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

  $(".edit-default-review-btn").click(function(e) {
    var modal = "#modalSaveCommentDefault";
    var id = $(this).attr("data-id");

    $(modal).on("shown.bs.modal", function() {
      var obj = $(this);

      $(".modal-title", obj).text("Edit Comment Default");
      $('input[name="id"]', obj).val(id);
      $.ajax({
        type: "get",
        url: appUrl + "/settings/comments-default-detail",
        dataType: "json",
        data: {
          id: id
        },
        success: function(data, statusText, xhr) {
          if ((data.status = "success")) {
            var cm = data.data;

            $(".btn-choice-start-" + cm.star).click();
            $('input[name="author"]', obj).val(cm.author);
            $('input[name="star"]', obj).val(cm.star);
            $('textarea[name="content"]', obj).val(cm.content);
            $(".box-select-country", obj).html(data.selectHtml);

            $(".select-multi", obj).multiselect({
              nonSelectedText: "Choose Language",
              // maxHeight: 222,
              // buttonWidth: '180px',
              buttonContainer: '<div class="selected-parents-container"></div>',
              enableFiltering: true,
              includeSelectAllOption: true,
              enableCaseInsensitiveFiltering: true
            });

            $("form", obj).submit();
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

  $("body").delegate("#formSaveCommentDefault", "submit", function(e) {
    e.preventDefault();
    $("#formSaveCommentDefault").validate({
      rules: {
        author: "required",
        star: {
          required: true,
          number: true,
          max: 5,
          min: 1
        },
        content: "required",
        country: "required"
      },
      /*errorPlacement: function (error, element) {
                element.attr('data-original-title', error.text())
                    .attr('data-toggle', 'tooltip')
                    .attr('data-placement', 'top');
                $(element).tooltip('show');
            },
            unhighlight: function (element) {
                $(element)
                    .removeAttr('data-toggle')
                    .removeAttr('data-original-title')
                    .removeAttr('data-placement')
                    .removeClass('error');
                $(element).unbind("tooltip");
            },*/
      submitHandler: function(form) {
        $.ajax({
          type: "post",
          url: appUrl + "/settings/save-comments-default",
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
              /*sweetAlert({
                                title : "Success!",
                                text : data.message,
                                type: "success"
                            },function () {
                                window.location.reload();
                            });*/

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

              setTimeout(function() {
                window.location.reload();
              }, 1);
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

  $(".delete-comment-default").click(function(e) {
    e.preventDefault();
    var modal = "#modalDeleteCommentDefault";
    var id = $(this).attr("data-id");

    $(modal).on("shown.bs.modal", function() {
      var obj = $(this);
      $('input[name="id"]', obj).val(id);
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

  $("body").delegate("#formDeleteCommentDefault", "submit", function(e) {
    e.preventDefault();
    var obj = $(this);
    var id = $('input[name="id"]', obj).val();

    $.ajax({
      type: "post",
      url: appUrl + "/settings/delete-comments-default",
      dataType: "json",
      data: obj.serialize(),
      beforeSend: function() {
        $("input, button, textarea", obj)
          .attr("disabled", true)
          .css("opacity", 0.5);
      },
      success: function(data, statusText, xhr) {
        $("input, button, textarea", obj)
          .attr("disabled", false)
          .css("opacity", 1);
        if (data.status == "success") {
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

          $("#modalDeleteCommentDefault").modal("hide");

          $(".ars-table-row-cmd-" + id).remove();
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

  $("body").delegate(".btn-choice-start", "click", function(e) {
    var obj = $(this);
    var star = obj.attr("data-star");
    $(".btn-choice-start i")
      .removeClass("icon-star-2")
      .addClass("icon-star-empty-1");

    for (i = 1; i <= star; i++) {
      $(".btn-choice-start-" + i)
        .find("i")
        .removeClass("icon-star-empty-1")
        .addClass("icon-star-2");
    }

    obj
      .closest("form")
      .find('input[name="star"]')
      .val(star);
  });

  $("#switch-default-review").on("change", function(ev) {
    var obj = $(this);
    $(".default-review-list-wrap").toggleClass("active");

    var status = 0;
    if (this.checked) {
      status = 1;
    }
    $.ajax({
      type: "post",
      url: appUrl + "/settings/status-comments-default",
      dataType: "json",
      data: {
        is_comment_default: status
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

  /*$('#form-save-random-review').validate({
        rules : {
            "rand_comment_default[from]" : {
                required : true,
                number : true,
                //max : limitCommentDefault,
                min : 1
            },
            "rand_comment_default[to]" : {
                required : true,
                number : true,
                max : limitCommentDefault,
                min : 1
            }
        },
        submitHandler: function (form) {
            $.ajax({
                type : "post",
                url : appUrl+'/settings/random-comments-default',
                dataType : 'json',
                data : $(form).serialize(),
                beforeSend: function () {
                    $('input, button, textarea', $(form)).attr('disabled', true).css('opacity', 0.5);
                },
                success: function (data, statusText, xhr) {
                    $('input, button, textarea', $(form)).attr('disabled', false).css('opacity', 1);
                    if (data.status == 'success'){
                        $.notify({
                            message: data.message
                        },{
                            z_index: 999999,
                            timer: 2000,
                            type: 'success'
                        });

                        setTimeout(function () {
                            window.location.reload();
                        },1);
                    }else{
                        $.notify({
                            message: data.message
                        },{
                            z_index: 999999,
                            timer: 2000,
                            type: 'danger'
                        });
                    }
                },
                error : function () {
                    $('input, button, textarea', $(form)).attr('disabled', false).css('opacity', 1);
                    $.notify({
                        message: lang_reviews.notice_error
                    },{
                        z_index: 999999,
                        timer: 2000,
                        type: 'danger'
                    });
                }
            });
        }
    });*/

  $(".importDefaultReviews").click(function(e) {
    $("#modalImportCommentDefault").modal({
      show: "true"
    });
  });

  $(".delete-imported-reviews").click(e => {
    $("#modalDeleteCommentImportedDefault").modal({ show: "true" });
  });

  $("body").delegate("#formImportCommentDefault", "submit", function(e) {
    e.preventDefault();
    var obj = $(this);

    $.ajax({
      type: "post",
      url: appUrl + "/settings/import-comments-default",
      dataType: "json",
      data: obj.serialize(),
      beforeSend: function() {
        $("input, button, textarea", obj)
          .attr("disabled", true)
          .css("opacity", 0.5);
      },
      success: function(data, statusText, xhr) {
        $("input, button, textarea", obj)
          .attr("disabled", false)
          .css("opacity", 1);
        if (data.status == "success") {
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

          $("#modalImportCommentDefault").modal("hide");
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
});

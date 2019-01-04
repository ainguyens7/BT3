jQuery(document).ready(function($) {
  // $("#form_settings").validate({
  //   rules: {
  //     "setting[get_max_number_review]": {
  //       required: true,
  //       number: true,
  //       min: 1,
  //       max: 1500
  //     },
  //     "setting[max_number_per_page]": {
  //       required: true,
  //       number: true,
  //       min: 1,
  //       max: 50
  //     },
  //     "setting[country_get_review][]": "required",
  //     style: "required"
  //   }
  // });

  $("#form_settings [type='submit']").on('click', function() {
    let hasErrors = 0;

    // Max number review
    const valMaxNumberReview = $('[name="setting[get_max_number_review]"]').val();
    let errorMaxNumberReview = $('#get_max_number_review_error');
    errorMaxNumberReview.text('');
    if(valMaxNumberReview == "") {
      errorMaxNumberReview.text('This field is required.');
      hasErrors++;
    } else if(isNaN(valMaxNumberReview)) {
      errorMaxNumberReview.text('Please enter a valid number.');
      hasErrors++;
    } else if(valMaxNumberReview < 1 || valMaxNumberReview > 1500) {
      errorMaxNumberReview.text('The number must be from 0 to 1500');
      hasErrors++;
    }

    // Country
    const valCountry = $('[name="setting[country_get_review][]"]').val();
    let errorCountry = $('#country_get_review_error');
    errorCountry.text('');
    if(valCountry == "") {
      errorCountry.text('This field is required.');
      hasErrors++;
    }

      // gender percent
      var gender_percent_error = $('.gender_percent_error');
      const male_percent = $('input[name="setting[male_name_percent]"]').val();
      if(male_percent > 100 || male_percent < 0){
          gender_percent_error.text('The number must be from 0 to 100.');
          hasErrors++;
      }
    
    // Auto publish reviews
    let errorApproveReview = $('#approve-review-manually-error');
    const listItemApproveReviewStar = $('[name="setting[approve_review_stars][]"]');
    errorApproveReview.text('');
    if ( $('#approve-review-manually').is(':checked') && $(listItemApproveReviewStar).is(':checked') === false ) {
      errorApproveReview.text('You must select at least 1 star option.');
      hasErrors++;
    }

    if(hasErrors > 0) {
      return false;
    }

    return true;
  });

  $("#form-update-app button").on("click", function(e) {
    e.preventDefault();
    let obj = $(this);

    $.ajax({
      type: "post",
      url: appUrl + "/updateHandle/",
      dataType: "json",
      data: obj.serialize(),
      beforeSend: function() {
        $(obj).text("Updating...");
        $(obj)
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

          window.location.href = data.url;
        } else {
          $(obj).text("Update App");
          $(obj)
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
        $(obj).text("Update App");
        $(obj)
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

  $("#form-critical-update-app button").on("click", function(e) {
    e.preventDefault();
    var obj = $(this);

    $.ajax({
      type: "post",
      url: appUrl + "/cs/critical-update",
      dataType: "json",
      data: obj.serialize(),
      beforeSend: function() {
        $(obj).text("Updating...");
        $(obj)
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

          window.location.href = data.url;
        } else {
          $(obj).text("Update App");
          $(obj)
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
        $(obj).text("Update App");
        $(obj)
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

  $("#form-internal-update button").on("click", function(e) {
    e.preventDefault();
    var obj = $(this);

    $.ajax({
      type: "post",
      url: appUrl + "/internal-update-handle/",
      dataType: "json",
      data: obj.serialize(),
      beforeSend: function() {
        $(obj).text("Updating...");
        $(obj)
          .attr("disabled", true)
          .css("opacity", 0.5);
      },
      success: function(data, statusText, xhr) {
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

          window.location.href = data.url;
        } else {
          $(obj).text("Update App");
          $(obj)
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
        $(obj).text("Update App");
        $(obj)
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

  $("#form-update-themes button").on("click", function(e) {
    e.preventDefault();
    var obj = $(this);

    $.ajax({
      type: "post",
      url: appUrl + "/updateThemesHandle/",
      dataType: "json",
      data: obj.serialize(),
      beforeSend: function() {
        $(obj).text("Updating...");
        $(obj)
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

          window.location.href = data.url;
        } else {
          $(obj).text("Update");
          $(obj)
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
        $(obj).text("Update");
        $(obj)
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

  $(".resetLocalTranslate").click(function(e) {
    $("#modalResetLocalTranslate").modal("show");
  });

  $("#formResetLocalTranslate").submit(function(e) {
    e.preventDefault();
    var obj = $(this);

    $.ajax({
      type: "post",
      url: appUrl + "/settings/reset-local-translate",
      dataType: "json",
      data: obj.serialize(),
      beforeSend: function() {
        $(obj)
          .attr("disabled", true)
          .css("opacity", 0.5);
      },
      success: function(data, statusText, xhr) {
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

          window.location.reload();
        } else {
          $(obj)
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
        $(obj)
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
  $(".convertingUser").click(function(e) {
    $("#convertingUser").modal("show");
  });

  //$('#addDefaultReviewsModal').modal("show");

  if ($(".theme-store-wrap .popular-block").hasClass("disabled")) {
    $(
      'input[type="radio"]',
      $(".theme-store-wrap .popular-block.disabled")
    ).remove();
  }

  $('input[name="setting[male_name_percent]"]').bind('keyup keydown mouseup change', function () {
    var female_name_percent = 100 - $(this).val();
    $('input[name="setting[female_name_percent]"]').val(female_name_percent);
  });

  $('#approve-review-manually').change(function () {
      if ($(this).is(':checked')) {
          $('.approve-review-stars').show();
      }else{
          $('.approve-review-stars').hide();
      }
  });
  $('form#form_update_theme').submit(function(e){
      var form = $(this);
      e.preventDefault();

      var url = form.attr('action');
      $.ajax({
          type: "POST",
          url: url,
          success: function(data)
          {
            if(data.status){
                $.notify(
                    {
                        message: data.messages
                    },
                    {
                        z_index: 999999,
                        timer: 2000,
                        type: "success"
                    }
                );

                location.reload();
            }else {
                $.notify(
                    {
                        message: "An error occurred"
                    },
                    {
                        z_index: 999999,
                        timer: 2000,
                        type: "danger"
                    }
                );
            }
          }
      });
  })
    $('form#form_update_settings').submit(function(e){
        var form = $(this);
        e.preventDefault();

        var url = form.attr('action');
        $.ajax({
            type: "POST",
            url: url,
            success: function(data)
            {
                if(data.status){
                    $.notify(
                        {
                            message: data.messages
                        },
                        {
                            z_index: 999999,
                            timer: 2000,
                            type: "success"
                        }
                    );

                    location.reload();
                }else {
                    $.notify(
                        {
                            message: "An error occurred"
                        },
                        {
                            z_index: 999999,
                            timer: 2000,
                            type: "danger"
                        }
                    );
                }
            }
        });
    })

    $('form#form_update_products').submit(function(e){
        var form = $(this);
        e.preventDefault();

        var url = form.attr('action');
        $.ajax({
            type: "POST",
            url: url,
            beforeSend: function() {
                form.attr("disabled", true).css("opacity", 0.5);
                form.find('button').attr('disabled','disabled');
            },
            success: function(data)
            {
                form.attr("disabled", false).css("opacity", 1);

                if(data.status){
                    $.notify(
                        {
                            message: data.messages
                        },
                        {
                            z_index: 999999,
                            timer: 2000,
                            type: "success"
                        }
                    );

                    location.reload();
                }else {
                    form.find('button').attr('disabled',false);

                    $.notify(
                        {
                            message: "An error occurred"
                        },
                        {
                            z_index: 999999,
                            timer: 2000,
                            type: "danger"
                        }
                    );
                }
            }
        });
    })

    $('form#form_update_master').submit(function(e){
        var form = $(this);
        e.preventDefault();

        var url = form.attr('action');
        $.ajax({
            type: "POST",
            url: url,
            success: function(data)
            {
                if(data.status){
                    $.notify(
                        {
                            message: data.messages
                        },
                        {
                            z_index: 999999,
                            timer: 2000,
                            type: "success"
                        }
                    );

                    location.reload();
                }else {
                    $.notify(
                        {
                            message: "An error occurred"
                        },
                        {
                            z_index: 999999,
                            timer: 2000,
                            type: "danger"
                        }
                    );
                }
            }
        });
    })
});

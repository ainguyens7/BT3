jQuery(document).ready(function($) {
  var vm = new Vue({
    el: "#form_ThemesSettings",
    data: {
      _this_vue: "",
      style_customer_review: "",

      style_font_number_rating: "",
      style_background_number_rating: "",
      style_font_star_rating: "",
      style_background_star_rating: "",
      style_star_color: "",
      style_star_button_background: "",

      style_font_notify_review: "",
      style_background_notify_review: "",
      cp_bg_font_notify_review: "",

      style_font_submit_review: "",
      style_background_submit_review: "",
      cp_bg_font_submit_review: ""
    },
    mounted: function() {
      this.colorStyleCustomerText();
      _this_vue = this;
    },
    methods: {
      colorStyleCustomerText() {
        var _this = this;
        var _color = $("#colorStyleCustomerText").data("color");
        $("#colorStyleCustomerText").ColorPicker({
          color: _color,
          onBeforeShow() {
            $(".palette-choose-wrap").hide();
          },
          onChange(hsb, hex, rgb) {
            $("#style_customer_review").val(`#${hex}`);
            _this.style_customer_review = `color: #${hex}`;
          }
        });
      },
      showPaletteChoose(event) {
        var _this = _this_vue;
        var target = event.currentTarget;
        if (
          $(target)
            .parent()
            .find(".palette-choose-wrap")
            .is(":hidden")
        ) {
          $(target)
            .parents(".palette-color-wrap")
            .find(".palette-choose-wrap.active")
            .hide();
          $(target)
            .parent()
            .find(".palette-choose-wrap")
            .addClass("active")
            .toggle();
        } else {
          $(target)
            .parents(".palette-color-wrap")
            .find(".palette-choose-wrap.active")
            .hide();
        }

        var label_font = $(target).data("font");
        var label_background = $(target).data("background");
        var label_background_2 = $(target).data("background_2");
        _this.styleFontElement(label_font);
        _this.styleBackgroundElement(label_background);
        if (label_background_2) {
          _this.styleBackgroundElement(label_background_2);
        }
      },
      styleFontElement(elementId) {
        var _this = _this_vue;
        var target = document.getElementById(elementId);
        var _color = $(target).data("color");
        var type = $(target).data("type");
        switch (type) {
          case "style_font_number_rating":
            $(target).ColorPicker({
              color: _color,
              onChange: function(hsb, hex, rgb) {
                $("#style_font_number_rating").val(`#${hex}`);
                _this.style_font_number_rating = `color: #${hex}`;
                _this.style_background_star_rating = `background: #${hex}`;
              }
            });
            break;
          case "style_font_notify_review":
            $(target).ColorPicker({
              color: _color,
              onChange: function(hsb, hex, rgb) {
                $("#style_font_notify_review").val(`#${hex}`);
                _this.style_font_notify_review = `color: #${hex}`;
                _this.cp_bg_font_notify_review = `background: #${hex}`;
              },
              onHide: function() {
                // $('.palette-choose-wrap').hide();
              }
            });
            break;
          case "style_font_submit_review":
            $(target).ColorPicker({
              color: _color,
              onChange: function(hsb, hex, rgb) {
                $("#style_font_submit_review").val(`#${hex}`);
                _this.style_font_submit_review = `color: #${hex}`;
                _this.cp_bg_font_submit_review = `background: #${hex}`;
              },
              onHide: function() {
                // $('.palette-choose-wrap').hide();
              }
            });
            break;
          default:
            break;
        }
      },
      styleBackgroundElement(elementId) {
        var _this = this;
        var target = document.getElementById(elementId);
        var _color = $(target).data("color");
        var type = $(target).data("type");
        switch (type) {
          case "style_background_number_rating":
            $(target).ColorPicker({
              color: _color,
              onChange: function(hsb, hex, rgb) {
                $("#style_background_number_rating").val(`#${hex}`);
                _this.style_background_number_rating = `background: #${hex}`;
                //_this.style_font_star_rating = `color: #${hex}`;
              },
              onHide: function() {
                // $('.palette-choose-wrap').hide();
              }
            });
            break;

          case "style_star_color":
            $(target).ColorPicker({
              color: _color,
              onChange: function(hsb, hex, rgb) {
                $("#style_star_color").val(`#${hex}`);
                _this.style_star_color = `color: #${hex}`;
                _this.style_star_button_background = `background: #${hex}`;
              },
              onHide: function() {
                // $('.palette-choose-wrap').hide();
              }
            });
            break;

          case "style_background_notify_review":
            $(target).ColorPicker({
              color: _color,
              onChange: function(hsb, hex, rgb) {
                $("#style_background_notify_review").val(`#${hex}`);
                _this.style_background_notify_review = `background: #${hex}`;
              },
              onHide: function() {
                // $('.palette-choose-wrap').hide();
              }
            });
            break;
          case "style_background_submit_review":
            $(target).ColorPicker({
              color: _color,
              onChange: function(hsb, hex, rgb) {
                $("#style_background_submit_review").val(`#${hex}`);
                _this.style_background_submit_review = `background: #${hex}`;
              },
              onHide: function() {
                // $('.palette-choose-wrap').hide();
              }
            });
            break;
          default:
            break;
        }
      }
    }
  });

  $(".resetThemeSetting").click(function(e) {
    $("#modalResetThemeSetting").modal("show");
  });

  $("#formResetThemeSetting").submit(function(e) {
    e.preventDefault();
    var obj = $(this);

    $.ajax({
      type: "post",
      url: appUrl + "/settings/reset-themes-settings",
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

  $(".preview-style").click(function(e) {
    //$('#modalPreviewStyle').modal("show");
    var modal = "#modalPreviewStyle";
    var image = $(this).attr("data-image");
    $(modal).on("shown.bs.modal", function() {
      var obj = $(this);
      $("img", obj).attr("src", image);
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

  $("#active-advance-mode").on("change", function(e) {
    var obj = $(this);
    if (this.checked) {
      console.log("check");
      $(".advance-mode-wrap").addClass("active");
    } else {
      console.log("nocheck");
      $(".advance-mode-wrap").removeClass("active");
    }
  });
});

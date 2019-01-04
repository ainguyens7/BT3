// only themesSettings.blade
window.addEventListener('load', function() {
    init();
    validateThemeSetting();

    setTimeout(function() {
        changeStyleReviews( $('select[name="style"]').val() );
    }, 500);
});

function init() {
    displayReviewImages($('#checkbox-display-review-images').is(":checked"));
    displayUserName($('#checkbox-display-use-name').is(":checked"));
    displayCountry($('#checkbox-display-country').is(":checked"));
    displayRating($('#active-display_rate_list').is(":checked"));
    displayAbstractImagesAvatar($('#checkbox-display-use-avatar').is(":checked"));
    displayFormatDate($('select[name="setting[date_format]"] option:selected').text());
    displayAvatar($('select[name="style_customize[avatar]"]').val());
    displayRatingCard($('select[name="rating_card"]').val());
    displayStyleBox($('[name="rating_point"]').val());
    displayRatingColor();
    displayVerifyColor();
    displayBoxColor();
    displayDate();
    displayCodeCSS();
    displayAdvanceSort();
}

function validateThemeSetting() {
    const formThemeSetting = document.querySelector('#form-theme-settings');

    formThemeSetting && formThemeSetting.addEventListener('submit', function(e) {
        e.preventDefault();
        let hasError = 0;

        // Number per page
        const NumberPerPage = document.getElementsByName('setting[max_number_per_page]')[0];
        const lblNumberPerPage = document.getElementById('setting[max_number_per_page]-error');

        if( 0 < parseInt(NumberPerPage.value) && parseInt(NumberPerPage.value) <= 20) {
            lblNumberPerPage.style.display = "none";
        } else {
            NumberPerPage.focus();
            lblNumberPerPage.style.display = "block";
            hasError++;
        }

        // Check has errors
        if(parseInt(hasError) === 0) {
            this.submit();
        }
    })
}

$('#preview-real').on('shown.bs.modal', function () {
    const _wrapper = $('.wrapper-preview-real').parent().html();
    $('#showCurrentPreviewReal').html(_wrapper);
})

$('select[name="rating_point"]').on('change', function() {
    const classDefault = 'alr-point';
    switch(parseInt($(this).val())) {
        case 1:
            $('#realSummaryBox').attr('class', classDefault);
            break;
        case 2:
            $('#realSummaryBox').attr('class', classDefault + ' circle');
            break;
        default: 'Summarybox change!';
    }
})

$('select[name="style_customize[avatar]"]').on('change', function() {
    displayAvatar($(this).val());
})

function displayAvatar(styleAvatar) {
    const classDefault = 'alr-point';
    switch(styleAvatar) {
        case 'square':
            $('.alr-avatar > img').attr('class', classDefault);
            break;
        case 'circle':
            $('.alr-avatar > img').attr('class', classDefault + ' img-circle');
            break;
        default: 'Avatar change!';
    }
}

function displayDate() {
    if(!$('[name="setting[active_date_format]"]').is(':checked')) {
        $('.format-date').addClass('undate');
        $('select[name="setting[date_format]"]').parent().addClass('only_available');
    } else {
        $('select[name="setting[date_format]"]').parent().removeClass('only_available');
    }
}

function displayRatingCard(styleRating) {
    const classBackgroundDefault = 'rating-symbol-background';
    const classForegroundDefault = '';
    switch(parseInt(styleRating)) {
        case 1:
            // star
            $('.rating-symbol-background').attr('class', classBackgroundDefault + " alr-icon-star");
            $('.rating-symbol-foreground > span').attr('class', classForegroundDefault + " alr-icon-star");
            $('.alr-star i').attr('class', classForegroundDefault + " alr-icon-star");
            break;
        case 2:
            // favorite
            $('.rating-symbol-background').attr('class', classBackgroundDefault + " alr-icon-favorite");
            $('.rating-symbol-foreground > span').attr('class', classForegroundDefault + " alr-icon-favorite");
            $('.alr-star i').attr('class', classForegroundDefault + " alr-icon-favorite");
            break;
        case 3:
            // unit
            $('.rating-symbol-background').attr('class', classBackgroundDefault + " alr-icon-unit");
            $('.rating-symbol-foreground > span').attr('class', classForegroundDefault + " alr-icon-unit");
            $('.alr-star i').attr('class', classForegroundDefault + " alr-icon-unit");
            break;
        case 4:
             // notifications
             $('.rating-symbol-background').attr('class', classBackgroundDefault + " alr-icon-notifications");
             $('.rating-symbol-foreground > span').attr('class', classForegroundDefault + " alr-icon-notifications");
             $('.alr-star i').attr('class', classForegroundDefault + " alr-icon-notifications");
            break;
        case 5:
            // like
            $('.rating-symbol-background').attr('class', classBackgroundDefault + " alr-icon-like");
            $('.rating-symbol-foreground > span').attr('class', classForegroundDefault + " alr-icon-like");
            $('.alr-star i').attr('class', classForegroundDefault + " alr-icon-like");
            break;
        default: 'Summarybox change!';
    }
}

$('select[name="rating_card"]').on('change', function() {
    displayRatingCard($(this).val());
})  

$('select[name="style"]').on('change', function() {
    changeStyleReviews($(this).val())
});

$('select[name="setting[date_format]"]').on('change', function() {
    const formatDate = $(this).find('option:selected').text();
    $('.format-date').attr('date', displayFormatDate(formatDate));
});

$('#active-display_rate_list').on('change', function() {
    displayRating($(this).is(":checked"));
});

$('#checkbox-display-country').on('change', function() {
    displayCountry($(this).is(":checked"));
});

$('#checkbox-display-review-images').on('change', function() {
    displayReviewImages($(this).is(":checked"));
});

$('#checkbox-display-use-name').on('change', function() {
    displayUserName($(this).is(":checked"));
});

$('#checkbox-display-use-avatar').on('change', function() {
    displayAbstractImagesAvatar($(this).is(":checked"));
});

function changeStyleReviews(styleReviews) {
    const style = styleReviews;
    const classDefault = 'alr-wrap-list-rv';
    if( $('.grid.alr-wrap-list-rv').length ) {
        let msnry = new Masonry(document.querySelector('.grid.alr-wrap-list-rv'), {
            itemSelector: '.alr-grid-item'
        });
        msnry.destroy();
    }
    switch(parseInt(style)) {
        case 2:
            // list
            $('#realLayoutStyle').attr('class', classDefault + ' list');
            break;
        case 5:
            // grid
            $('#realLayoutStyle').attr('class', classDefault + ' grid');
            msnry = new Masonry(document.querySelector('.grid.alr-wrap-list-rv'), {
                itemSelector: '.alr-grid-item'
            });
            break;
        default: 'Summarybox change!';
    }
}

function displayFormatDate(textFormat) {
    $('.format-date').attr('date', textFormat);
}

function displayAbstractImagesAvatar(boolean) {
    if(!boolean) {
        $('.alr-avatar img').hide();
        $('select[name="style_customize[avatar]"]').parent().addClass('only_available');
    } else {
        $('.alr-avatar img').show();
        $('.alr-avatar img').attr('src',  $('.alr-avatar img').attr('img-user'));
        $('select[name="style_customize[avatar]"]').parent().removeClass('only_available');
    }
}

function displayRating(boolean) {
    if(boolean) {
        $('.alr-count-reviews').removeClass('hidden');
    } else {
        $('.alr-count-reviews').addClass('hidden');
    }
}

function displayCountry(boolean) {
    if(boolean) {
        $('.alr-flag').removeClass('hidden');
    } else {
        $('.alr-flag').addClass('hidden');
    }
}

function displayReviewImages(boolean) {
    if(boolean) {
        $('.alr-thumbnail').removeClass('hidden');
        $('.alr-img').removeClass('hidden');
    } else {
        $('.alr-thumbnail').addClass('hidden');
        $('.alr-img').addClass('hidden');
    }

    if($('select[name="style"]').val() == '5')
        changeStyleReviews(5);
}

function displayUserName(boolean) {
    if(!boolean) {
        var name = hiddenCustomName($('.arl-name-guest').attr('name-default'));
        $('.arl-name-guest').text(name);
    } else {
        var name = $('.arl-name-guest').attr('name-default');
        $('.arl-name-guest').text(name);
    }
}

function displayRatingColor() {
    let color = $('[name="style_customize[icon_color]"]').val();
    $('.rating-symbol-foreground > span').css('color', color);
    $('.alr-summary .alr-count-reviews .alr-progress-bar > div').css('backgroundColor', color);
    $('.alr-star i').css('color', color);
}

function displayVerifyColor() {
    let color = $('[name="style_customize[verify_color]"]').val();
    $('.alr-verified').css('color', color);
}

function displayStyleBox(styleBox) {
    const classDefault = 'alr-point';
    switch(parseInt(styleBox)) {
        case 1:
            $('#realSummaryBox').attr('class', classDefault);
            break;
        case 2:
            $('#realSummaryBox').attr('class', classDefault + ' circle');
            break;
        default: 'Summarybox change!';
    }
}

function displayAdvanceSort() {
    if( $('[name="setting[display_advance_sort]"]').is(":checked") ) {
        $('.preview-real__content .advance-sort').show();
    } else {
        $('.preview-real__content .advance-sort').hide();
    }

    $('[name="setting[display_advance_sort]"]').on('change', function() {
        if( $('[name="setting[display_advance_sort]"]').is(":checked") ) {
            $('.preview-real__content .advance-sort').show();
        } else {
            $('.preview-real__content .advance-sort').hide();
        }
    })
}

function displayBoxColor() {
    let color = $('[name="style_customize[box_color]"]').val();
    $('#realSummaryBox').css('background', color);
}

$('[name="setting[active_date_format]"]').on('change', function() {
    $('.format-date').toggleClass('undate');

    if($(this).is(':checked')) {
        $('select[name="setting[date_format]"]').parent().removeClass('only_available');
    } else {
        $('select[name="setting[date_format]"]').parent().addClass('only_available');
    }
});

$('#realBoxColor').colorpicker().on('changeColor', function(e) {
    $('#realSummaryBox').css('backgroundColor', e.color.toString('rgba'));
});

$('#realVerifyColor').colorpicker().on('changeColor', function(e) {
    $('.alr-verified').css('color', e.color.toString('rgba'));
});

$('#realIconColor').colorpicker().on('changeColor', function(e) {
    $('.rating-symbol-foreground > span').css('color', e.color.toString('rgba'));
    $('.alr-summary .alr-count-reviews .alr-progress-bar > div').css('backgroundColor', e.color.toString('rgba'));
    $('.alr-star i').css('color', e.color.toString('rgba'));
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

function displayCodeCSS() {
    if($('[name="is_code_css"]').is(':checked')) {
        $('#style-advance-mode + .CodeMirror').show();
    } else {
        $('#style-advance-mode + .CodeMirror').hide();
    }
}

$('[name="is_code_css"]').on('change', function() {
    if($('[name="is_code_css"]').is(':checked')) {
        $('#style-advance-mode + .CodeMirror').show();
    } else {
        $('#style-advance-mode + .CodeMirror').hide();
    }
});

function hiddenCustomName(name) {
	var res = name ? name.split("") : [];
    for(i = 1; i < res.length; i++) {
        res[i] = res[i] == ' ' ? res[i] : '*';
    }
    return res.join('');
}
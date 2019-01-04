
$('body').delegate('#upload-review-photo', 'change', function (e) {
    e.stopPropagation();
    var obj = jQuery(this);
    var files = e.target.files;
    var files_length = files.length;
    var files_item = 0;
    var total_img = parseInt($('.wrapper-up-photo__item').length + files_length);
    
    if (total_img > 5) {
        alert('You can only upload up to 5 photos.');
        return;
    }

    if (!window.File || !window.FileReader || !window.FileList || !window.Blob) {
        alert('The File APIs are not fully supported in this browser.');
        return;
    }

    if (!files) {
        console.error("This browser doesn't seem to support the `files` property of file inputs.");
        return;
    }

    if( ! files[0].name.match(/\.(jpg|jpeg|png|gif)$/i) ){
        alert('Sorry, this file format is not supported. Only jpg, png and gif are accepted.');
        return;
    }

    if (files[0].size && files[0].size > 1024 * 1024 * 10) {
        alert('The image must be under 10MB.');
        return;
    }

    $.each(files, function (key, value, files) {
        var data = new FormData();
        data.append(key, value);
       
        $.ajax({
            url: appUrl + '/review/uploadfile',
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            beforeSend: function () {
                obj.attr('disabled', true);
                $('.ali_loading_upload').fadeIn();
            },
            success: function (data, statusText, xhr) {
                ++files_item;
                obj.attr('disabled', false);

                obj.val();
                if (data.status == "success") {
                    var  html_img = '<div class="wrapper-up-photo__item d-inline-block">' +
                        '<img src="' + data.image_thumb_url + '" class="img-rounded preview-result"> ' +
                        '<span class="remove-review-photo" data-image_name="'+data.image_name+'"><i class="material-icons">close</i></span> ' +
                        '<input type="hidden" name="img[]" value="'+data.image_url +'">' +
                        '</div>';

                    $('.review-photo-list').prepend(html_img);

                    if(files_item >= files_length) {
                        $('.ali_loading_upload').fadeOut();
                    }

                } else {
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
                obj.val();
                obj.attr('disabled', false);
                $('.ali_loading_upload').fadeOut();
                $.notify({
                    message: lang_reviews.notice_error
                },{
                    z_index: 999999,
                    timer: 2000,
                    type: 'danger'
                });
            }
        })

    });

    obj.val('');
});


var processing_delete_img = 0;
$('body').delegate('.remove-review-photo', 'click', function (e) {
    var obj = $(this);
    var image_name = obj.attr('data-image_name');
    if (processing_delete_img == 0){
        if (image_name){
            $.ajax({
                url: appUrl + '/review/deletefile',
                type: 'POST',
                dataType: 'json',
                data: {
                    image_name : image_name
                },
                beforeSend: function () {
                    $('.ali_loading_upload').fadeIn();
                    processing_delete_img = 1;
                },
                success: function (data, statusText, xhr) {
                    processing_delete_img = 0;
                    if (data.status == "success") {
                        $('.ctn-upload-img').show();
                        obj.closest('.wrapper-up-photo__item').remove();
                    } else {
                        $.notify({
                            message: data.message
                        },{
                            z_index: 999999,
                            timer: 2000,
                            type: 'danger'
                        });
                    }
                    $('.ali_loading_upload').fadeOut();
                },
                error : function () {
                    $.notify({
                        message: lang_reviews.notice_error
                    },{
                        z_index: 999999,
                        timer: 2000,
                        type: 'danger'
                    });
                }
            });
        }else{
            obj.closest('.wrapper-up-photo__item').remove();
            $('.ctn-upload-img').show();
        }
    }
});

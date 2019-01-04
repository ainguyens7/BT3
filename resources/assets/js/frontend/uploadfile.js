import config from './config';

// export default function uploadFileFrontEnd() {
//   window.URL = window.URL || window.webkitURL;

//   if(window.URL) {
//     uploadFileFrontEndPreview();
//   } else {
//     uploadFileFrontEndDefault();
//   }
// }

// function uploadFileFrontEndDefault() {
//   $("#alireview_file_upload").on("change", function(e) {
//     e.stopPropagation();
//     var obj = $(this);
//     var files_item = 0;
//     var files = e.target.files;
//     var files_length = files.length;
//     var alireview_shop_id = $('input[name="alireview_shop_id"]').val();

//     var ctn_image = $(".alireview_list_image");
//     var total_img = parseInt( $("li", ctn_image).length + files_length )

//     if (total_img > 5) {
//       return alert("You can only upload up to 5 photos.");
//     }

//     if (
//       !window.File ||
//       !window.FileReader ||
//       !window.FileList ||
//       !window.Blob
//     ) 
//     {
//       return alert("The File APIs are not fully supported in this browser.");
//     }

//     var files = e.target.files;
//     if (!files) {
//       return console.error("This browser doesn't seem to support the `files` property of file inputs.");
//     }

//     if (!files[0].name.match(/\.(jpg|jpeg|png|gif)$/i)) {
//       return alert("Sorry, this file format is not supported. Only jpg, png and gif are accepted.");
//     }

//     if (files[0].size && files[0].size > 1024 * 1024 * 10) {
//       return alert("The image must be under 10MB.");
//     }

//     $.each(files, function(key, value) {
//       var data = new FormData();
//       data.append(key, value);
//       console.log(data);

//       $.ajax({
//         url: config.shop_url + "/comment/upload_img?alireview_shop_id=" + alireview_shop_id,
//         type: "POST",
//         data: data,
//         cache: false,
//         dataType: "json",
//         processData: false, // Don't process the files
//         contentType: false, // Set content type to false as $ will tell the server its a query string request
//         beforeSend: function() {
//           obj.attr("disabled", true);
//           $(".alireview_loading_upload").fadeIn();
//         },
//         success: function(data, statusText, xhr) {
//           ++files_item;
//           obj.attr("disabled", false);
//           obj.val();

//           if(files_item >= files_length) {
//             $('.alireview_loading_upload').fadeOut();
//           }

//           if (data.status == "success") {
//             var html_image =
//               "<li>" +
//               '<a href="javascript:void(0)" class="alireview_btn_delete_image" data-image_name="' +
//               data.image_name +
//               '" title="Remove this image">x</a>' +
//               '<img src="' +
//               data.image_thumb_url +
//               '" alt="" style="max-width: 100%">' +
//               '<input type="hidden" name="alireview_img[]" value="' +
//               data.image_url +
//               '">' +
//               "</li>";

//             ctn_image.append(html_image);
//           } else {
//             alert(data.message);
//           }
//         },
//         error: function() {
//           obj.val();
//           obj.attr("disabled", false);
//           $(".alireview_loading_upload").fadeOut();
//           alert("An error, please check  and try again.");
//         }
//       });
//     });

//     obj.val("");
//   });

//   var processing_delete_img = 0;
//   $("body").delegate(".alireview_btn_delete_image", "click", function(e) {
//     var obj = $(this);
//     var alireview_shop_id = $('input[name="alireview_shop_id"]').val();
//     var image_name = obj.attr("data-image_name");
//     var self = $(this);
//     if (processing_delete_img == 0) {
//       $.ajax({
//         url: config.shop_url + "/comment/delete_img",
//         type: "POST",
//         dataType: "json",
//         data: {
//           alireview_shop_id: alireview_shop_id,
//           image_name: image_name
//         },
//         beforeSend: function() {
//           processing_delete_img = 1;
//           $(self).closest('li').css({
//             'opacity': 0.5,
//             'pointer-events': 'none'
//           });
//         },
//         success: function(data, statusText, xhr) {
//           processing_delete_img = 0;
//           if (data.status == "success") {
//             $(".alireview-file-name").html("");
//             obj.closest("li").remove();
//           } else {
//             alert(data.message);
//           }
//         },
//         error: function() {
//           processing_delete_img = 0;
//           alert("An error, please check  and try again.");
//         }
//       });
//     }
//   });
// }

export default function fileUploader(listImageWrap, inputFile, filesToUpload) {
  this.listImageWrap = listImageWrap; // Element dùng để appent photo vừa upload
  this.inputFile = inputFile; // Input:file
  this.fileIdCounter = 0;
  this.filesToUpload = filesToUpload; // Chứa từng new FormData
  this.sectionIdentifier = 'files1'; // Đặt tên cho hình vừa hiển thị client
  this.output = []; // Nhiều <li> chứa img + button remove
}

fileUploader.prototype.Init = function() {
  this.PreviewThumbnail();
  this.RemoveItem();
}

fileUploader.prototype.PreviewThumbnail = function() {
  window.URL = window.URL || window.webkitURL;

  const self = this;
  const listImageWrap = this.listImageWrap;
  const inputFile = this.inputFile;

  $(inputFile).on('change', function (e) {
    const files = e.target.files;
    const total_img = parseInt( $("li", listImageWrap).length + files.length );

    if (total_img > 5) {
      alert("You can only upload up to 5 photos.");
      return false;
    }

    if (!files) {
      console.error("This browser doesn't seem to support the `files` property of file inputs.");
      return false;
    }

    if (
      !window.File ||
      !window.FileReader ||
      !window.FileList ||
      !window.Blob
    ) 
    {
      alert("The File APIs are not fully supported in this browser.");
      return false;
    }

    for (let i = 0; i < files.length; i++) {
      if(!self.Validate(e, files[i])) {
        continue;
      }

      self.fileIdCounter++;
      const file = files[i];
      const fileId = self.sectionIdentifier + self.fileIdCounter + Math.random(5);

      self.filesToUpload.push({
          id: fileId,
          file: file
      });

      let li  = `<li>
                  <span class="alireview_del_img" data-fileid="${fileId}"> x </span>
                  <img src="${window.URL.createObjectURL(file)}" data-fileid="${fileId}" />
                </li>`;
      
      self.output.push(li);
  };
  $(listImageWrap).html(self.output.join(""));


    e.target.value = null;
  });
}

fileUploader.prototype.RemoveItem = function() {
  const self = this;
  const listImageWrap = this.listImageWrap;
  
  $(listImageWrap).on("click", '.alireview_del_img', function (e) {
      e.preventDefault();

      var fileId = $(this).attr('data-fileid');

      for (var i = 0; i < self.filesToUpload.length; ++i) {
          if (self.filesToUpload[i].id === fileId) {
              self.filesToUpload.splice(i, 1);
              self.output.splice(self.output.indexOf(fileId), 1);
          }
      }

      $(this).closest('li').remove();
  });
}

fileUploader.prototype.Clear = function() {
  const listImageWrap = this.listImageWrap;
  for (var i = 0; i < this.filesToUpload.length; ++i) {
      if (this.filesToUpload[i].id.indexOf(this.sectionIdentifier) >= 0)
          this.filesToUpload.splice(i, 1);
  }

  // Reset all variable default
  this.output = [];
  this.filesToUpload = [];
  $(listImageWrap).empty();
}

fileUploader.prototype.Validate = function(e, file) {
  // const files = e.target.files;

  if (!file.name.match(/\.(jpg|jpeg|png|gif)$/i)) {
    alert("Sorry, this file format is not supported. Only jpg, png and gif are accepted.");
    return false;
  }
  
  if (file.size && file.size > 1024 * 1024 * 10) {
    alert(`The image [${file.name}] must be under 10MB.`);
    return false;
  }

  return true;
}

fileUploader.prototype.UploadOneFile = function(formData, photoID) {
  return new Promise(function(resolve, reject) {
    $.ajax({
      url: config.shop_url + "/comment/upload_img?alireview_shop_id=" + $('input[name="alireview_shop_id"]').val(),
      type: "POST",
      data: formData,
      cache: false,
      dataType: "json",
      processData: false,
      contentType: false, 
      success: function(data) {
        if (data.status == "success") {
          let input = `<input type="hidden" name="alireview_img[]" value="${ data.image_url }" />`;
          $("#alireview_file_upload").closest('form').append(input);
          return resolve();
        
        }

        // Dùng để test
        // let input = `<input type="hidden" name="alireview_img[]" value="" />`;
        // $( input ).insertAfter( $('img[data-fileid="' + photoID + '"]') );
        // return resolve();
      }
    });
  });
}

fileUploader.prototype.Upload = function() {
  const listImageWrap = this.listImageWrap;
  
  $(listImageWrap).find('.alireview_del_img').css('display', 'none');
  return Promise.all(this.filesToUpload.map( item => {
      let formData = new FormData();
      formData.append("files", item.file);

      return this.UploadOneFile(formData, item.id);
    })
  )
  .then(result => {
    this.Clear();
  })
  .catch(error => { 
    console.log(error);
  });
}
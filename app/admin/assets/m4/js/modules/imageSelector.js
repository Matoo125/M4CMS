
self.imageSelector = {
  modal: function (callback) {
    $("#ImageSelector").modal('show');
    $("#ImageSelector button.btn-select").click(function (e) {
      e.preventDefault()
      if (activeTab === 1) {
        imageSelector.upload(function (data) {
          callback(data)
        })
      }
      else if (activeTab === 2) {
          imageSelector.link(function(data) {
            callback(data)
          })
      }
      else if (activeTab === 3) {
          imageSelector.gallery(function(data) {
            callback(data)
          })
      }
      $(this).unbind('click')
      $("#ImageSelector").modal('hide');
    })
  },
  folder: function () {
    var id = document.querySelector('input[name=id]').value
    var folder = document.querySelector('input[name=folder]').value;
    if (typeof parseInt(id) === 'number' && isFinite(parseInt(id))) {
      folder += '/' + id;
    } else {
      folder += '/' + document.querySelector('input[name=tmp_id]').value;
    }
    return folder
  },
  upload: function (callback) {
    //  var imgForm = document.getElementById('imgForm');
      formData = new FormData();
      formData.append('file', document.getElementById('imgForm').files[0]);
      formData.append('folder', imageSelector.folder());
      console.log(formData);
      $.ajax({
          type: 'POST',
          url: '/admin/media/upload',
          data: formData,
          contentType: false,
          processData: false,
          success:function(data){
              console.log("success");
              console.log(data);
             // console.log('/public/uploads/' + data.filename)
              callback(data)
             // return data.filename
          },
          error: function(data){
              console.log("error");
              console.log(data);
          }
      });
  },
  link: function (callback) {
    var link = document.querySelector('input[name=link]').value
    console.log(link)
    $.post('/admin/media/downloadLink', {
      link: link,
      folder: imageSelector.folder()
    }).done(function (r) {
      console.log(r)
      callback(r)
    }).fail(function (e) {
      console.log(e)
    })

  },
  gallery: function (callback) {
    console.log('getting image id '+ galleryModalSelectedImage)
    $.get('/admin/media/chooseFromGallery', {
      id: galleryModalSelectedImage,
      folder: imageSelector.folder()
    }).done(function(r) {
      console.log(r)
      callback(r)
    }).fail(function(e) {
      console.log(e)
    })
  }
 
}
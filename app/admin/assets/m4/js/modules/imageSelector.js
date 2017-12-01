import store from './storage'

var imageSelector = {
  selected: null, // id of image selected from media
  modal: function (callback) {
    $("#ImageSelector").modal('show'); // show modal
    // add click function to select button
    $("#ImageSelector button.btn-select")
    .click((e) => {
      e.preventDefault()
      if (store.activeTab === 1) {
        this.uploadSingle(function (data) {
          callback(data)
        })
      }
      else if (store.activeTab === 2) {
        this.link(function(data) {
          callback(data)
        })
      }
      else if (store.activeTab === 3) {
        this.media(function(data) {
          callback(data)
        })
      }
      else if (store.activeTab === 4) {
        this.uploadMultiple(function(data) {
          callback(data)
        })
      }
      $("#ImageSelector button.btn-select").unbind('click')
    }) // end of click function
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
  uploadSingle: function (callback) {
      var formData = new FormData();
      formData.append('file', document.getElementById('imgInput').files[0])
      formData.append('folder', this.folder());
      console.log(formData);
      $.ajax({
          type: 'POST',
          url: '/admin/media/upload',
          data: formData,
          contentType: false,
          processData: false,
          success:function(data){
              console.log("success")
              console.log(data)
              callback(data)
          },
          error: function(data){
              console.log("error")
              console.log(data)
          }
      })
  },
  uploadMultiple: function (callback) {
    var formData = new FormData()
    var files = document.getElementById('galleryInput').files
    for (var i = files.length - 1; i >= 0; i--) {
      formData.append('files[]', files[i])
    }
    formData.append('folder', this.folder())
    formData.append('galleryTitle', $('input[name=galleryTitle]').val())
    $.ajax({
      type: 'POST',
      url: '/admin/media/uploadMultiple',
      data: formData,
      contentType: false,
      processData: false,
      success: function (data) {
        console.log('upload multiple success')
        console.log(data)
        callback(data)
      },
      error: function (data) {
        console.log('error')
        console.log(data)
      }
    })
  },
  link: function (callback) {
    var link = document.querySelector('input[name=link]').value
    console.log(link)
    $.post('/admin/media/downloadLink', {
      link: link,
      folder: this.folder()
    }).done(function (r) {
      console.log(r)
      callback(r)
    }).fail(function (e) {
      console.log(e)
    })

  },
  media: function (callback) {
    console.log('getting image id '+ this.selected)
    $.get('/admin/media/chooseFromGallery', {
      id: this.selected,
      folder: this.folder()
    }).done(function(r) {
      console.log(r)
      callback(r)
    }).fail(function(e) {
      console.log(e)
    })
  },
  loadMedia: function () {
    $.get('/api/media/list').done(function(r) {
      var media = document.querySelector('#mediaSelect')
      for (var x = 0; x < r.media.length; x++) {
        var el = document.createElement('img')
        el.src = '/public/uploads/' + r.media[x].folder + '/' + r.media[x].filename
        el.width = 100
        el.setAttribute('data-id', r.media[x].id)
        el.setAttribute('class', 'img-thumbnail')
        el.onclick = function () {
          m4.imageSelector.selected = this.dataset.id
          $('.img-selected').removeClass('img-selected'); // removes the previous selected class
          $(this).addClass('img-selected'); // adds the class to the clicked image
          console.log('clicked')
        }
        console.log(r.media[x])
        console.log(el)
        media.appendChild(el)
      }
      document.querySelector('#mediaSelect .loader').style = "display:none"
    }).fail(function(e) { console.log(e) }) 
  },
  selectHeaderImage: function () {
    this.modal(function (data) {
      document.querySelector('#headerImage img').src = (
        '/public/uploads/' + data.folder + '/' + data.filename
      )
      document.querySelector('#headerImage input').value = data.id
      toastr.success(data.message)
      $("#ImageSelector").modal('hide');
    })
  }
 
}

module.exports = imageSelector
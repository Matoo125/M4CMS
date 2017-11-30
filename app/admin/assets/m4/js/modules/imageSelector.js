import store from './storage'

var imageSelector = {
  selected: null, // id of image selected from media
  modal: function (callback) {
    $("#ImageSelector").modal('show');
    $("#ImageSelector button.btn-select").click(function (e) {
      e.preventDefault()
      if (store.activeTab === 1) {
        imageSelector.upload(function (data) {
          callback(data)
        })
      }
      else if (store.activeTab === 2) {
          imageSelector.link(function(data) {
            callback(data)
          })
      }
      else if (store.activeTab === 3) {
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
              callback(data)
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
    console.log('getting image id '+ this.selected)
    $.get('/admin/media/chooseFromGallery', {
      id: this.selected,
      folder: imageSelector.folder()
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
    imageSelector.modal(function (data) {
      document.querySelector('#headerImage img').src = (
        '/public/uploads/' + data.folder + '/' + data.filename
      )
      document.querySelector('#headerImage input').value = data.id
      toastr.success(data.message)
    })
  }
 
}

module.exports = imageSelector
var activeTab = 1
var galleryModalSelectedImage = null
var loadGallery = null

var m4 = {
  data: {

  },
  plugins: {
    
  }
}

document.addEventListener("DOMContentLoaded", function(event) {

$(document).on('shown.bs.modal', function (e) {
  activeTab = 1
})

$(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
  activeTab = $(e.target).data('id')
})

$(document).on('change','.file', function () {
  console.log('file selected ')
  var val = $(this).val().replace(/C:\\fakepath\\/i,'');
  $(".filename").val(val);
})

/*
* setup quill handler
* @param   {String}  content to start with
* @return  {Object}  quill instance
*/
setupQuill = function(content) {

  console.log('setup quill function called');

  // toolbar definition
  var toolbar = [
    [{
      'header': [1, 2, 3, 4, 5, 6, false]
    }],

    ['bold', 'italic', 'underline', 'strike'],
    ['blockquote', 'code-block'],

    [{
      'align': []
    }],
    ['link', 'image'],
    ['clean']
  ];

  // image handler
  function quillImageHandler() {
    imageSelector.modal(function(data) {
      var range = quill.getSelection()
      var img = '/public/uploads/' + data.folder + '/' + data.filename
      quill.insertEmbed(range.index, 'image', img, Quill.sources.USER)
    })
  }

  // quill instance
  var quill = new Quill('#editor', {
    theme: 'snow',
    modules: {
      toolbar: {
        container: toolbar,
        handlers: {
          image: quillImageHandler
        }
      }
    }
  });

  // paste content ** check if is delta in use **
  if (content.indexOf('{') !== 0) {
    quill.clipboard.dangerouslyPasteHTML(0, content);

  } else {
    console.log(content)
    quill.setContents(JSON.parse(content));
  }

  // return instance
  return quill;

}

/*
*  Page
*  @param  {String}  method  Method to be called.              Required
*  @param  {Object}  quill   Quill object to get content from
*/
page = function(method, quill) {

  console.log('page function called')

  $("form .submit").click(function() {
    formData = {
      id: $('input[name=id]').val(),
      title: $("input[name=title]").val(),
      description: $("textarea[name=description").val(),
      content: quill.root.innerHTML,
      content_delta: JSON.stringify(quill.getContents()),
      image_id: $('#headerImage input').val(),
      is_published: $("input[name=is_published]").is(':checked'),
      tmp_id: $('input[name=tmp_id]').val()
    }
    console.log(formData)
    $.ajax({
      type: 'post',
      url: '/admin/pages/' + method,
      data: formData
    }).done(function(response) {
      console.log(response)
      toastr.success(response.message)
      if (method == 'createAjax') {
        router.go('/admin/pages/update/' + response.id)
      }
    }).fail(function(xhr, err) {
      console.log(err)
      console.log(xhr.responseJSON)
      toastr.error(xhr.responseJSON.message)
    })
  })

}

/*
*    Category
*    @param  {String}  Method to be called.  Required
*/
category = function(method) {

  console.log('category function called')

  $(".select-page").select2({
    theme: "bootstrap",
    minimumResultsForSearch: Infinity,
  });

  $('form .submit').click(function() {
    formData = {
      id: document.querySelector('input[name=id]').value,
      page_id: document.querySelector('.select-page').value,
      title: $("input[name=title]").val(),
      description: $("textarea[name=description").val(),
      image_id: document.querySelector('#headerImage input').value,
      tmp_id: document.querySelector('input[name=tmp_id]').value      
    }
    console.log(formData)
    $.ajax({
      type: 'post',
      url: '/admin/categories/' + method,
      data: formData
    }).done(function(response) {
      console.log(response)
      toastr.success(response.message)
      if (method == 'createAjax') {
        router.go('/admin/categories/update/' + response.id)
      }
    }).fail(function(xhr, err) {
      console.log(err)
      console.log(xhr.responseJSON)
      toastr.error(xhr.responseJSON.message)
    })
  })
}


/*
*  Post form initialization
*  @param  {String}  method  Method to be called.              Required
*  @param  {Object}  quill   Quill object to get content from
*/
post = function(method, quill) {

  console.log('post function called')

  $(".select-page").select2({
    theme: "bootstrap",
    minimumResultsForSearch: Infinity,
  });

  $(".select-category").select2({
    theme: "bootstrap",
    minimumResultsForSearch: Infinity,
  });

  // show categories of selected page
  $(".select-page").change(function () {
    console.log('page was selected ' + $(this).val())
    $.get('/api/categories/listBasic/' + $(this).val(), {})
    .done(function (r) {
      console.log(r.categories)
      var c_select = document.querySelector('.select-category')
      if (r.categories.length > 0) {
        document.querySelector('.select-category').parentElement.setAttribute('style','display:block')
        document.querySelector('.select-category').innerHTML = '<option value=""></option>'
      } else {
        document.querySelector('.select-category').parentElement.setAttribute('style','display:none')
        document.querySelector('.select-category').value = null
      }
      for (i = r.categories.length - 1; i >= 0; i--) {
        var o = document.createElement('OPTION')
        o.setAttribute('value', r.categories[i].value)
        o.innerHTML = r.categories[i].label
        document.querySelector('.select-category').append(o)
        console.log(r.categories[i])
      }
    }).fail(function(err) { console.log(err) })
  })

  $("form .submit").click(function() {
    formData = {
      id: document.querySelector('input[name=id]').value,
      title: $("input[name=title]").val(),
      description: $("textarea[name=description").val(),
      content: quill.root.innerHTML,
      content_delta: JSON.stringify(quill.getContents()),
      image_id: document.querySelector('#headerImage input').value,
      page_id: document.querySelector('.select-page').value,
      category_id: document.querySelector('.select-category').value,
      is_published: $("input[name=is_published]").is(':checked'),
      tmp_id: document.querySelector('input[name=tmp_id]').value      
    }
    console.log(formData)
    $.ajax({
      type: 'post',
      url: '/admin/posts/' + method,
      data: formData
    }).done(function(response) {
      console.log(response)
      toastr.success(response.message)
      if (method == 'createAjax') {
        router.go('/admin/posts/update/' + response.id)
      }
    }).fail(function(xhr, err) {
      console.log(err)
      console.log(xhr)
      console.log(xhr.responseJSON)
      toastr.error(xhr.responseJSON.message)
    })
  })

  return true;

}

settings = function () {

  console.log('settings function has been called. ')

  $('form .submit').click(function () {
    formData = {
      title: $('input[name=title]').val(),
      description: $('input[name=desc]').val(),
      theme: $('select[name=theme]').val()
    }

    console.log($('input#title').val())

    $.post('/admin/settings/update', formData)
    .done(function (response) {
      console.log(response)
      toastr.success(response.message)
    }).fail (function (xhr, error) {
      console.log(error)
      toastr.error(xhr.responseJSON.message)
    })
  })

  return true;
}

loadGallery = function () {
  console.log('loading gallery')
  $.get('/api/media/list').done(function(r) {
    var gallery = document.querySelector('#gallery')
    for (x = 0; x < r.media.length; x++) {
      var el = document.createElement('img')
      el.src = '/public/uploads/' + r.media[x].folder + '/' + r.media[x].filename
      el.width = 100
      el.setAttribute('data-id', r.media[x].id)
      el.setAttribute('class', 'img-thumbnail')
      el.onclick = function () {
        galleryModalSelectedImage = this.dataset.id
        $('.img-selected').removeClass('img-selected'); // removes the previous selected class
        $(this).addClass('img-selected'); // adds the class to the clicked image
        console.log('clicked')
      }
      console.log(r.media[x])
      console.log(el)
      gallery.appendChild(el)
    }
    document.querySelector('#gallery .loader').style = "display:none"
  }).fail(function(e) { console.log(e) }) 
}

}); // end of onload

/*
 * Get HTML asynchronously
 * @param  {String}   url       The URL to get HTML from
 * @param  {Function} callback  A callback function. Pass in "response" variable to use returned HTML.
 */
getHTML = function(url, callback) {

  // Feature detection
  if (!window.XMLHttpRequest) return;

  // Create new request
  var xhr = new XMLHttpRequest();

  // Setup callback
  xhr.onload = function() {
    if (callback && typeof(callback) === 'function') {
      callback(this.responseXML);
    }
  }

  // Get the HTML
  xhr.open('GET', url);
  xhr.responseType = 'document';
  xhr.send();

};

/*
 * Router object
 */
router = {
  /*
   *  Method go
   *  @param  {String}  URL to visit.
   */
  go: function(url) {

    getHTML(url, function(response) {

      console.log('response is delivered')
      var customScript = document.getElementById('customScript')
      if (customScript) customScript.remove()
      runThisOnLoad = null

      document.getElementById('app').innerHTML = response.getElementById('app').innerHTML;


      if (response.getElementById('customScript')) {
        var newdiv = document.createElement('div');
        var script = document.createElement('script');
        script.id = 'customScript';
        script.innerHTML = response.getElementById('customScript').innerHTML;
        newdiv.appendChild(script);
        document.getElementById('app').appendChild(newdiv);

      } else {
        console.log('no custom script to run')
        console.log(response)
        r = response
      }

      if (typeof runThisOnLoad === 'function') {
        runThisOnLoad()
      } else {
        console.log('run this on load is not a function')
      }

    });

    history.pushState(null, null, url)
    console.log(url)

  }
}

window.onpopstate = function(event) {
  router.go(window.location)
};


function selectHeaderImage () {
  imageSelector.modal(function (data) {
    document.querySelector('#headerImage img').src = (
      '/public/uploads/' + data.folder + '/' + data.filename
    )
    document.querySelector('#headerImage input').value = data.id
    toastr.success(data.message)
  })
}

var imageSelector = {
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



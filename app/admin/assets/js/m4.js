document.addEventListener("DOMContentLoaded", function(event) {

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
          var range = page.quill.getSelection()
          var img = '/public/uploads/' + data.filename
          page.quill.insertEmbed(range.index, 'image', img, Quill.sources.USER)
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

      // paste content
      quill.clipboard.dangerouslyPasteHTML(0, content);

      // return instance
      return quill;

    }

    /*
     *  Page form initialization
     *  @param  {String}  method  Method to be called.              Required
     *  @param  {Object}  quill   Quill object to get content from
     */
    page = function(method, quill) {

        console.log('page function called')

        $("form .submit").click(function() {
          formData = {
            id: document.querySelector('input[name=id]').value,
            title: $("input[name=title]").val(),
            description: $("textarea[name=description").val(),
            content: quill.root.innerHTML,
            image_id: document.querySelector('#headerImage input').value,
            is_published: $("input[name=is_published]").is(':checked')
          }
          console.log(formData)
          $.ajax({
            type: 'post',
            url: '/admin/pages/' + method,
            data: formData
          }).done(function(response) {
            console.log(response)
            toastr.success(response.message)
          }).fail(function(xhr, err) {
            console.log(err)
            console.log(xhr.responseJSON)
            toastr.error(xhr.responseJSON.message)
          })
        })

        return true;

      }


      /*
       *    Category form initialization
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
          }
          console.log(formData)
          $.ajax({
            type: 'post',
            url: '/admin/categories/' + method,
            data: formData
          }).done(function(response) {
            console.log(response)
            toastr.success(response.message)
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
              image_id: document.querySelector('#headerImage input').value,
              page_id: document.querySelector('.select-page').value,
              category_id: document.querySelector('.select-category').value,
              is_published: $("input[name=is_published]").is(':checked')
            }
            console.log(formData)
            $.ajax({
              type: 'post',
              url: '/admin/posts/' + method,
              data: formData
            }).done(function(response) {
              console.log(response)
              toastr.success(response.message)
            }).fail(function(xhr, err) {
              console.log(err)
              console.log(xhr)
              console.log(xhr.responseJSON)
              toastr.error(xhr.responseJSON.message)
            })
          })

          return true;

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
   *  @param  {String}  url   URL to visit.
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



/* 
 * This redirects user to correct page
 * if she uses hash in url
 */
/*
if (window.location.pathname === '/admin/') {
  var location = '/admin' + window.location.hash.substr(1)
  router.go(location)
  console.log('i should go here: ' + location)
  history.pushState(null, null, location)
}
*/


window.onpopstate = function(event) {
  router.go(window.location)
};


function selectHeaderImage () {
  imageSelector.modal(function (data) {
    document.querySelector('#headerImage img').src = '/public/uploads/' + data.filename
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
          imageSelector.link()
      }

      else if (activeTab === 3) {
          imageSelector.gallery()
      }

      $(this).unbind('click')
      $("#ImageSelector").modal('hide');

    })

  },
  upload: function (callback) {
    //  var imgForm = document.getElementById('imgForm');
      formData = new FormData();
      formData.append('file', document.getElementById('imgForm').files[0]);

      $.ajax({
          type: 'POST',
          url: '/admin/media/create',
          data: formData,
          cache: false,
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
  link: function () {

  },
  gallery: function () {

  }
}

var activeTab = 1
$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
  activeTab = $(e.target).data('id')
})

$(".fileUploadBrowseButton").click(function () {
  $(".file").click()
})

$(".file").change(function () {
  var val = $(this).val().replace(/C:\\fakepath\\/i,'');
  $(".filename").val(val);
})
document.addEventListener("DOMContentLoaded", function(event) { 


  setupQuill = function (quill, content) {

    console.log('setup quill function called');

    var toolbar = [
      [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

      ['bold', 'italic', 'underline', 'strike'], 
      ['blockquote', 'code-block'],

      [{ 'align': [] }],
      ['link', 'image'],
      ['clean']
    ];


    function quillImageHandler () {
        imageSelector.modal(function (data) {
          var range = page.quill.getSelection()
          var img = '/public/uploads/' + data.filename
          page.quill.insertEmbed(range.index, 'image', img, Quill.sources.USER)
      })
    }

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

    quill.clipboard.dangerouslyPasteHTML(0, content);


    return quill;

  } 

  page = function (method, quill) {

    console.log('page function called')

     $("form .submit").click(function () {
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
       }).done(function (response) {
         console.log(response)
         toastr.success(response.message)
       }).fail(function (xhr, err) {
         console.log(err)
         console.log(xhr.responseJSON)
         toastr.error(xhr.responseJSON.message)
       })
     })

     return true;

   }

   /*
    * Get HTML asynchronously
    * @param  {String}   url       The URL to get HTML from
    * @param  {Function} callback  A callback function. Pass in "response" variable to use returned HTML.
    */
   getHTML = function ( url, callback ) {

       // Feature detection
       if ( !window.XMLHttpRequest ) return;

       // Create new request
       var xhr = new XMLHttpRequest();

       // Setup callback
       xhr.onload = function() {
           if ( callback && typeof( callback ) === 'function' ) {
               callback( this.responseXML );
           }
       }

       // Get the HTML
       xhr.open( 'GET', url );
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
    go: function (url) {

      getHTML( url, function (response) { 

          console.log('response is delivered')
          var customScript = document.getElementById('customScript')
          if (customScript) customScript.remove()
          runThisOnLoad = null

          document.getElementById('app').innerHTML =  response.getElementById('app').innerHTML;


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
          } else { console.log('run this on load is not a function')  }

      });
    }
   }



    /* 
     * This redirects user to correct page
     * if she uses hash in url
    */
    router.go('/admin' + window.location.hash.substr(1))
    console.log('i should to here: ' + '/admin' + window.location.hash.substr(1))


});


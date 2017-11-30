import imageSelector from './imageSelector'

/*
* setup quill handler
* @param   {String}  content to start with
* @return  {Object}  quill instance
*/
var setupQuill = function (content) {

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


module.exports = setupQuill
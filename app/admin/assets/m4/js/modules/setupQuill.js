import imageSelector from './imageSelector'

/*
* setup quill handler
* @param   {String}  content to start with
* @return  {Object}  quill instance
*/
var setupQuill = function (content) {

  console.log('setup quill function called');

  // image handler
  function quillImageHandler() {
    var range = quill.getSelection()
    if (!range) { return alert('Please select your position in editor') }
    $('#uploadGalleryLink').show()
    imageSelector.modal(function(data) {
      if (m4.data.activeTab === 4) {
        var gallery = '[' + 'gallery id="' + data.gallery.id + '"]'
        quill.insertText(range.index, gallery)
      } else {
        var img = '/public/uploads/' + data.folder + '/' + data.filename
        quill.insertEmbed(range.index, 'image', img, Quill.sources.USER)
      }
      $("#ImageSelector").modal('hide');
    })
  }

  // quill instance
  var quill = new Quill('#editor', {
    theme: 'snow',
    modules: {
      toolbar: {
        container: '#quillToolbar',
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
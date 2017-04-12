// editor settings
$('#textareaArticleBody').trumbowyg({
  autogrow: true,
  btnsDef: {
    // Customizables dropdowns
    image: {
        dropdown: ['insertImage', 'upload', 'noembed'],
        ico: 'insertImage'
    }
  },
  btns: [
  ['viewHTML'],
  ['formatting'],
  'btnGrp-semantic',
  ['superscript', 'subscript'],
  ['link'],
  ['image'],
  'btnGrp-justify',
  'btnGrp-lists',
  ['horizontalRule'],
  ['removeformat'],
  ['fullscreen'],
],
  plugins: {
      upload: {
        serverPath: 'https://api.imgur.com/3/image',
        fileFieldName: 'image',
        headers: {'Authorization': 'Client-ID 64b1a2b1d70254e'},
        urlPropertyName: 'data.link'
      }
  }

});

// to update image path
// after image is selected
var input = $("#image").change(function(){
  var filename = this.value.split("\\").pop();
  $(this).next('.form-control-file').addClass("selected").html(filename);

})

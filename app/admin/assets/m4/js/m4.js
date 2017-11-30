self.activeTab = 1
self.galleryModalSelectedImage = null
self.loadMedia = null
self.runThisOnLoad = null

self.m4 = {
  data: {

  },
  plugins: {
    
  }
}


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

require('./modules/setupQuill.js')
require('./modules/page.js')
require('./modules/post.js')
require('./modules/category.js')
require('./modules/settings.js')
require('./modules/loadMedia.js')
require('./modules/getHTML.js')
require('./modules/router.js')
require('./modules/imageSelector.js')
require('./modules/selectHeaderImage.js')


window.onpopstate = function(event) {
  router.go(window.location)
}
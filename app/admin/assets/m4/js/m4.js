import setupQuill from './modules/setupQuill'
import settings from './modules/settings'
import router from './modules/router'
import post from './modules/post'
import page from './modules/page'
import imageSelector from './modules/imageSelector'
import category from './modules/category'
import storage from './modules/storage'

self.runThisOnLoad = null

module.exports = {
 data: storage, // storage
 plugins: {}, // for plugins
 router: router,
 post: post,
 page: page,
 category: category,
 settings: settings,
 setupQuill: setupQuill,
 imageSelector: imageSelector,
}

self.router = router


window.onpopstate = function(event) {
  router.go(window.location)
}
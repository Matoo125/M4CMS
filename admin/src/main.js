// === DEFAULT / CUSTOM STYLE ===
// WARNING! always comment out ONE of the two require() calls below.
// 1. use next line to activate CUSTOM STYLE (./src/themes)
// require(`./themes/app.${__THEME}.styl`)
// 2. or, use next line to activate DEFAULT QUASAR STYLE
require(`quasar/dist/quasar.${__THEME}.css`)
// ==============================

import Vue from 'vue'
import Quasar from 'quasar'
import router from './router'
import store from './storage'

Vue.use(Quasar) // Install Quasar Framework

const eventHub = new Vue() // event hub
Vue.prototype.$bus = eventHub

let checkSession = function () {
  store.dispatch('checkSession')
  setTimeout(function () {
    if (store.state.user.session === false) {
      router.push({ name: 'Login' })
    }
  }, 150)
}

router.beforeEach((to, from, next) => {
  if (store.state.user.session === false && to.name !== 'Login') {
    checkSession()
    next()
  }
  else {
    next()
  }
})

Quasar.start(() => {
  /* eslint-disable no-new */
  new Vue({
    el: '#q-app',
    router,
    store,
    render: h => h(require('./App'))
  })
})

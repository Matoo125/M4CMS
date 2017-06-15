import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

function load (component) {
  return () => System.import(`components/${component}.vue`)
}

export default new VueRouter({
  /*
   * NOTE! VueRouter "history" mode DOESN'T works for Cordova builds,
   * it is only to be used only for websites.
   *
   * If you decide to go with "history" mode, please also open /config/index.js
   * and set "build.publicPath" to something other than an empty string.
   * Example: '/' instead of current ''
   *
   * If switching back to default "hash" mode, don't forget to set the
   * build publicPath back to '' so Cordova builds work again.
   */

  routes: [
    {
      path: '/admin',
      component: load('admin/Skeleton'),
      children: [
        {
          path: '',
          component: load('admin/Home')
        },
        {
          path: 'pages',
          component: load('admin/pages/List')
        },
        {
          path: 'categories',
          component: load('admin/categories/List')
        },
        {
          path: 'posts',
          component: load('admin/posts/List')
        },
        {
          path: 'users',
          component: load('admin/Users')
        },
        {
          path: 'settings',
          component: load('admin/Settings')
        },
        {
          name: 'createPage',
          path: 'page/create',
          component: load('admin/pages/Add')
        },
        {
          path: 'page/:id',
          component: load('admin/pages/Update')
        }
      ]
    },
    // Default
    { path: '*', component: load('Error404') } // Not found
  ]
})

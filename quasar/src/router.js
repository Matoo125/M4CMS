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
      name: 'Admin',
      path: '/admin',
      component: load('admin/Skeleton'),
      children: [
        {
          name: 'AdminHome',
          path: '',
          component: load('admin/Home')
        },
        {
          name: 'AdminListPages',
          path: 'pages',
          component: load('admin/pages/List')
        },
        {
          name: 'createPage',
          path: 'page/create',
          component: load('admin/pages/Add')
        },
        {
          name: 'AdminUpdatePage',
          path: 'page/:id',
          component: load('admin/pages/Update')
        },
        {
          name: 'AdminListCategories',
          path: 'categories',
          component: load('admin/categories/List')
        },
        {
          name: 'AdminCreateCategory',
          path: 'category/create',
          component: load('admin/categories/Add')
        },
        {
          name: 'AdminUpdateCategory',
          path: 'category/:id',
          component: load('admin/categories/Update')
        },
        {
          name: 'AdminListPosts',
          path: 'posts',
          component: load('admin/posts/List')
        },
        {
          name: 'AdminCreatePost',
          path: 'post/create',
          component: load('admin/posts/Add')
        },
        {
          name: 'AdminPostUpdate',
          path: 'post/:id',
          component: load('admin/posts/Update')
        },
        {
          name: 'AdminListUsers',
          path: 'users',
          component: load('admin/Users')
        },
        {
          name: 'AdminSettings',
          path: 'settings',
          component: load('admin/Settings')
        }
      ]
    },
    // Default
    { path: '*', component: load('Error404') } // Not found
  ]
})

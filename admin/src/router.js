import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

function load (component) {
  return () => System.import(`components/${component}.vue`)
}

export default new VueRouter({
  base: '/admin',
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
      name: 'Login',
      path: '/login',
      component: load('Login')
    },
    {
      path: '/',
      component: load('Skeleton'),
      children: [
        {
          name: 'AdminHome',
          path: '',
          component: load('Home')
        },
        {
          name: 'AdminListPages',
          path: 'pages',
          component: load('pages/List')
        },
        {
          name: 'createPage',
          path: 'page/create',
          component: load('pages/Add')
        },
        {
          name: 'AdminUpdatePage',
          path: 'page/:id',
          component: load('pages/Update')
        },
        {
          name: 'AdminListCategories',
          path: 'categories',
          component: load('categories/List')
        },
        {
          name: 'AdminCreateCategory',
          path: 'category/create',
          component: load('categories/Add')
        },
        {
          name: 'AdminUpdateCategory',
          path: 'category/:id',
          component: load('categories/Update')
        },
        {
          name: 'AdminListPosts',
          path: 'posts',
          component: load('posts/List')
        },
        {
          name: 'AdminCreatePost',
          path: 'post/create',
          component: load('posts/Add')
        },
        {
          name: 'AdminUpdatePost',
          path: 'post/:id',
          component: load('posts/Update')
        },
        {
          name: 'AdminListUsers',
          path: 'users',
          component: load('users/List')
        },
        {
          name: 'AdminAddUser',
          path: 'user/create',
          component: load('users/Add')
        },
        {
          name: 'AdminUpdateUser',
          path: 'user/:id',
          component: load('users/Update')
        },
        {
          name: 'Media',
          path: 'media',
          component: load('media/Home')
        },
        {
          name: 'AdminSettings',
          path: 'settings',
          component: load('Settings')
        }
      ]
    },
    // Default
    { path: '*', component: load('Error404') } // Not found
  ]
})

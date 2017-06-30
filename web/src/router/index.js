import Vue from 'vue'
import Router from 'vue-router'
import Home from '@/components/Home'
import Page from '@/components/Page'
import Post from '@/components/Post'
import Category from '@/components/Category'

Vue.use(Router)

export default new Router({
  linkActiveClass: 'is-active',
  routes: [
    {
      path: '/',
      name: 'Home',
      component: Home
    },
    {
      path: '/page/:id',
      name: 'Page',
      component: Page
    },
    {
      path: '/post/:id?',
      name: 'Post',
      component: Post
    },
    {
      path: '/category/:id?',
      name: 'Category',
      component: Category
    }
  ],
  scrollBehavior (to, from, savedPosition) {
    return { x: 0, y: 0 }
  }
})

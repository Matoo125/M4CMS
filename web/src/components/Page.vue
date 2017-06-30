<template>
  <div v-if="page">
    <section class="hero is-success">
         <div class="hero-body">
           <div class="container has-text-centered">
             <h1 class="title is-2">
               {{ page.title }}
             </h1>
             <h2 class="subtitle is-4">
               {{ page.description }}
             </h2>
           </div>
         </div>
       </section>

       <section class="section blog">
         <div class="container">
           <div class="columns is-mobile">
             <div class="column is-8 is-offset-2">
               <div class="content">
                 <div class="section">
                   <div v-html="page.content"></div>
                 </div>

                 <!-- -->
                 <div class="section" v-for="category in categories">
                   <h2 class="is-title">{{ category.title }}</h2>
                      <div class="card post is-fullwidth" v-for="post in category.posts">
                         <header class="card-header has-text-centered ">
                           <p class="card-header-title">
                             {{ post.title }}
                           </p>
                         </header>
                         <div class="card-content">
                           <article class="media">
                             <div class="media-left">
                             </div>
                             <div class="media-content">
                               <div class="content">

                                 <p>
                                   {{ post.description }}
                                 </p>
                               </div>
                             </div>
                           </article>
                         </div>
                         <footer class="card-footer">
                           <router-link class="card-footer-item" :to="{ name: 'Post', params: { id: post.id }}">Read More</router-link>
                         </footer>
                       </div>
                 </div>


               </div>

             </div>
           </div>
         </div>
       </section>

  </div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'Page',
  data () {
    return {
      page: null,
      categories: null
    }
  },
  methods: {
    fetchContent () {
      axios.get(process.env.API + '/pages/id/' + this.$route.params.id)
      .then(response => {
        console.log(response.data)
        this.page = response.data
      }).catch(error => { console.log(error) })

      axios.get(process.env.API + '/categories/list/' + this.$route.params.id)
      .then(response => {
        console.log(response.data)
        this.categories = response.data
        this.fetchPosts()
      }).catch(error => { console.log(error) })
    },
    fetchPosts () {
      for (let category of this.categories) {
        axios.get(process.env.API + '/posts/listbycategory/' + category.id)
        .then(response => {
          console.log(response.data)
          category.posts = response.data
        })
      }
    }
  },
  watch: {
    '$route': 'fetchContent'
  },
  created () {
    this.fetchContent()
  },
  beforeDestroy () {
    this.page = null
    this.categories = null
  }
}
</script>

<style scoped>
  .post .card-header .card-header-title {
    display: block;
  }
</style>

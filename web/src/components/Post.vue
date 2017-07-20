<template>
  <div v-if="post.show">
    
    <section class="hero is-info is-medium" :style="{ 'background': image, 'background-size': 'cover' }">
      <div class="hero-body">
        <div class="container has-text-centered">
          <h1 class="title is-2">
            {{ post.title }}
          </h1>
          <h2 class="subtitle is-4">
            {{ post.description }}
          </h2>
        </div>
      </div>
    </section>

    <section class="section">
      <div class="container">
        <div class="columns is-mobile">
          <div class="column is-8 is-offset-2">

            <!-- columns start -->
            <div class="columns is-mobile is-multiline">
              <div class="column"> <!-- First Column -->
                <nav class="breadcrumb"> 
                    <ul>
                        <li>
                          <router-link :to="{ name: 'Page', params: { id: post.page_id }}">
                            {{ post.page }}
                          </router-link>
                        </li>
                        <li>
                          <router-link :to="{ name: 'Category', params: { id: post.category_id }}">
                            {{ post.category }}
                          </router-link>
                        </li>
                        <li class="is-active">
                          <a>{{ post.title }}</a>
                        </li>
                    </ul>
                </nav>
              </div>
              <div class="column"> <!-- Second Column -->
                <p class="has-text-right has-text-muted">{{ post.created_at}}</p>
              </div>
            </div>
            <!-- columns end -->

            <div class="content blog-post section">
              <article v-html="post.content"></article>
            </div>
            <div class="card is-fullwidth">
              <header class="card-header">
                <p class="card-header-title">
                  About the author
                </p>

              </header>
              <div class="card-content">
                <article class="media">
                  <div class="media-left">
                    <figure class="image is-64x64">
                      <img src="http://placehold.it/128x128" alt="Image">
                    </figure>
                  </div>
                  <div class="media-content">
                    <div class="content">
                      <p>
                        <strong>{{ post.author }}</strong> 
                        <br>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean efficitur sit amet massa fringilla egestas. Nullam condimentum luctus turpis.
                      </p>
                    </div>
                  </div>
                </article>
              </div>
              <footer class="card-footer">
                <a class="card-footer-item">Share on Facebook</a>
                <a class="card-footer-item">Share on Twitter</a>
                <a class="card-footer-item">Share on G+</a>
              </footer>
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
  name: 'Post',
  data () {
    return {
      post: {
        show: false
      }
    }
  },
  methods: {
    fetchPost () {
      window.scrollTo(0, 0)
      axios.get(process.env.API + 'posts/id/' + this.$route.params.id)
      .then(response => {
        console.log(response.data)
        this.post = response.data
        this.post.show = true
      }).catch(error => { console.log(error) })
    }
  },
  computed: {
    image () {
      if (this.post.image) {
        // linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('http://placehold.it/350x150')
        return 'linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url("' + process.env.UPLOADS + this.post.image + '") '
      }
      return null
    }
  },
  watch: {
    '$route': 'fetchPost'
  },
  created () {
    this.fetchPost()
  }
}
</script>

<style scoped>
  .content {
    padding-top: 0;
  }
</style>

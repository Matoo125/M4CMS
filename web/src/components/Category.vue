<template>
  <div v-if="category">
    <section class="hero is-danger">
      <div class="hero-body">
        <div class="container has-text-centered">
          <h2 class="title is-2">
            {{ category.title }}
          </h2>
          <h4 class="subtitle is-4">
            {{ category.description }}
          </h4>
        </div>
      </div>
    </section>

    <section class="section">
      <div class="container">
        <div class="columns">
          <div class="column" v-for="post in posts">
            <div class="card">
              <header class="card-header">
                <p class="card-header-title">
                  {{ post.title }}
                </p>
              </header>
              <div class="card-content">
                {{ post.description }}
              </div>
              <footer class="card-footer">
                <p class="card-footer-item">
                  <router-link :to="{ name: 'Post', params: { id: post.id } }">
                    Read More
                  </router-link>
                </p>
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
  name: 'Category',
  data () {
    return {
      category: null,
      posts: null
    }
  },
  methods: {
    fetchCategory () {
      axios.get(process.env.API + '/categories/id/' + this.$route.params.id)
      .then(response => {
        console.log(response.data)
        this.category = response.data
        this.fetchPosts()
      }).catch(error => { console.log(error) })
    },
    fetchPosts () {
      axios.get(process.env.API + '/posts/listByCategory/' + this.$route.params.id)
      .then(response => {
        console.log(response.data)
        this.posts = response.data
      }).catch(error => { console.log(error) })
    }
  },
  watched: {
    '$route': 'fetchCategory'
  },
  created () {
    this.fetchCategory()
  }
}
</script>

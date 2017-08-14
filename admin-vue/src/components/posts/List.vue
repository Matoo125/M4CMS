<template>
  <div class="row" v-if="posts">
  <table class="q-table loose striped-odd bordered cell-delimiter highlight">
    <thead>
      <tr>
        <th class="text-left">Title</th>
        <th class="text-right">Description</th>
        <th class="text-right">Published</th>
        <th class="text-right">Page</th>
        <th class="text-right">Category</th>        
        <th class="text-right">Author</th>        
        <th class="text-right">Last update</th>
      </tr>
    </thead>
    <tbody>
      <router-link tag="tr" :to="'/post/' + post.id" v-for="post in posts" :key="post.id">
        <td class="text-left">{{ post.title }}</td>
        <td class="text-right">{{ post.description }}</td>
        <td class="text-right">{{ is_published(post.is_published) }}</td>
        <td class="text-right">{{ post.page }}</td>
        <td class="text-right">{{ post.category }}</td>
        <td class="text-right">{{ post.author }}</td>
        <td class="text-right">{{ post.updated_at }}</td>
      </router-link>
    </tbody>
  </table>

  <router-link
    tag="button"
    class="primary circular absolute-bottom-right"
    style="right: 18px; bottom: 18px; font-size: 2em"
    to="post/create"
  >+</router-link>

  </div>
</template>

<script>
import axios from 'axios'
import { Loading } from 'quasar'
export default {
  mounted () {
    this.fetchpostsListData()
  },
  data () {
    return {
      posts: null
    }
  },
  methods: {
    fetchpostsListData () {
      Loading.show()
      axios.get(process.env.API + 'posts/list')
        .then(response => {
          console.log(response)
          this.posts = response.data
        })
        .catch(error => {
          console.log(error)
        })
      Loading.hide()
    },
    is_published (number) {
      return number === '0' ? 'no' : 'yes'
    }
  }

}
</script>

<style scoped>
  tbody, tbody > tr > td {
    cursor: pointer;
  }
</style>

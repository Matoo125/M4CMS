<template>
  <div class="card">
    <div class="card-title">
      Create Post
    </div>
    <div class="card-content generic-margin">
      <div class="form-input">
        <div class="stacked-label">
          <input class="full-width" v-model="post.title">
          <label>Title</label>
        </div>
      </div>
      
      <div class="form-input">
        <div class="stacked-label">
          <textarea class="full-width" v-model="post.description"></textarea>
          <label>Description</label>
        </div>
      </div>

      <div class="form-input">
        <div class="stacked-label">
          <q-chips v-model="tags"></q-chips>
          <label>Tags</label>
        </div>
      </div>

      <div class="form-input">
        <div class="form-input">
          <label for="">Content</label>
            <editor :content="post.content" @contentChange="value => { post.content = value }"></editor>
        </div>
      </div>

      <br>
      <trix-vue></trix-vue>
      <br>
      <div class="row justify-between">
        <div class="list no-border">
          <div class="item"><div class="item-content">
            Publish:   <q-toggle v-model="post.is_published"></q-toggle></div></div>
        </div>

        <div class="list no-border">
          <div class="item" v-if="categories">
            <div class="item-content">
              Category: 
              <q-select
                type="radio"
                v-model="post.category_id"
                :options="categories"
              ></q-select>
            </div>
          </div>
          <div class="item" v-if="pages">
            <div class="item-content">
              Page: 
              <q-select
                type="radio"
                v-model="post.page_id"
                :options="pages"
                @input="fetchCategoriesList()"
              ></q-select>
            </div>
          </div>
          <div class="item" v-if="authors">
            <div class="item-content">
              Author: 
              <q-select
                type="radio"
                v-model="post.author_id"
                :options="authors"
              ></q-select>
            </div>
          </div>
        </div>

        <div>
          <button class="primary" @click="create()">Create</button>
        </div>
        
      </div>

    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { Toast, Loading } from 'quasar'
import Editor from '../Editor.vue'

export default {
  name: 'AddPost',
  data () {
    return {
      categories: null,
      pages: null,
      authors: null,
      post: {
        content: '',
        is_published: true,
        tags: null,
        category_id: null,
        page_id: null,
        author_id: null
      }
    }
  },
  components: { Editor },
  computed: {
    tags: {
      get () {
        return this.post.tags ? this.post.tags.split(',') : []
      },
      set (value) {
        this.post.tags = value.join()
      }
    }
  },
  created () {
    this.fetchAuthorsList()
    this.fetchPagesList()
  },
  methods: {
    fetchCategoriesList () {
      axios.get(process.env.API + 'categories/ListBasic/' + this.post.page_id).then(response => {
        this.categories = response.data
        console.log(response.data)
      }).catch(error => { console.log(error) })
    },
    fetchPagesList () {
      axios.get(process.env.API + 'pages/ListBasic').then(response => {
        this.pages = response.data
        console.log(response.data)
      }).catch(error => { console.log(error) })
    },
    fetchAuthorsList () {
      axios.get(process.env.API + 'users/ListBasic').then(response => {
        this.authors = response.data
        console.log(response.data)
      }).catch(error => { console.log(error) })
    },
    create () {
      Loading.show()
      axios({
        method: 'post',
        url: process.env.API + 'posts/create',
        data: this.post
      })
      .then(response => {
        console.log(response)
        if (response.data.status === 'ERROR') {
          Toast.create.negative({html: response.data.message})
        }
        else {
          Toast.create.positive({html: response.data.message})
          this.$router.push(response.data.id)
        }
      })
      .catch(error => {
        console.log(error)
      })
      Loading.hide()
    }
  }
}
</script>

<style scoped>
  .form-input {
    margin-top: 20px;
  }
</style>

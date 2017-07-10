<template>
  <div class="card">
    <div class="card-title">
      Post Editor
    </div>
    <div class="card-content generic-margin">
      <div class="form-input">
        <div class="floating-label">
          <input required class="full-width" v-model="post.title">
          <label>Title</label>
        </div>
      </div>
      
      <div class="form-input">
        <div class="stacked-label">
          <textarea required class="full-width" v-model="post.description"></textarea>
          <label>Description</label>
        </div>
      </div>

      <div class="form-input">
         <label>Tags</label>
          <q-chips v-model="tags"></q-chips>
      </div>

      <div class="form-input">
        <div class="form-input">
          <label for="">Content</label>
            <editor :content="post.content" @contentChange="value => { post.content = value }"></editor>
        </div>
      </div>
      <br>
      
      <div class="row wrap justify-between">
        <div class="list no-border">
          <div class="item"><div class="item-content">
            Published:   <q-toggle v-model="post.is_published"></q-toggle></div></div>
          <div class="item"><div class="item-content">Created at: {{ post.created_at }}</div></div>
          <div class="item"><div class="item-content">Updated at: {{ post.updated_at }}</div></div>
        </div>

        <div class="list no-border">
          <div class="item">
            <div class="item-content">
              Category: 
              <q-select
                v-if="categories"
                type="radio"
                v-model="post.category_id"
                :options="categories"
              ></q-select>
              <button @click="fetchCategoriesList()" v-else>{{ post.category }}</button>
            </div>
          </div>
          <div class="item">
            <div class="item-content">
              Page: 
              <q-select
                v-if="pages"
                type="radio"
                v-model="post.page_id"
                :options="pages"
                @input="fetchCategoriesList()"
              ></q-select>
              <button @click="fetchPagesList()" v-else>{{ post.page }}</button>
            </div>
          </div>
          <div class="item">
            <div class="item-content">
              Author: 
              <q-select
                v-if="authors"
                type="radio"
                v-model="post.author_id"
                :options="authors"
              ></q-select>
              <button @click="fetchAuthorsList()" v-else>{{ post.author }}</button>
            </div>
          </div>
        </div>

        <div>
          <div>
            <button class="button primary" @click="$refs.ImageModal.toggleModal()">Image</button>
          </div>
          <br>
          <div v-if="image">
            <img :src="image" class="responsive" alt="">
          </div>
          <ImageModal ref="ImageModal" @imageSelected="imageSelected"></ImageModal>
        </div>

        <div>
          <button class="primary" @click="updateIt()">Update</button>
          <button class="red" @click="remove()">Delete</button>
        </div>
        
      </div>

    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { Toast, Loading, Dialog } from 'quasar'
import Editor from '../Editor.vue'
import ImageModal from '../modals/Image.vue'

export default {
  components: { Editor, ImageModal },
  data () {
    return {
      image: null,
      categories: null,
      pages: null,
      authors: null,
      post: {
        is_published: false,
        tags: '',
        image_id: null
      }
    }
  },
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
    this.fetchPostData()
  },
  methods: {
    fetchCategoriesList () {
      Loading.show()
      axios.get(process.env.API + 'categories/ListBasic/' + this.post.page_id).then(response => {
        this.categories = response.data
        console.log(response.data)
      }).catch(error => { console.log(error) })
      Loading.hide()
    },
    fetchPagesList () {
      Loading.show()
      axios.get(process.env.API + 'pages/ListBasic').then(response => {
        this.pages = response.data
        console.log(response.data)
      }).catch(error => { console.log(error) })
      Loading.hide()
    },
    fetchAuthorsList () {
      Loading.show()
      axios.get(process.env.API + 'users/ListBasic').then(response => {
        this.authors = response.data
        console.log(response.data)
      }).catch(error => { console.log(error) })
      Loading.hide()
    },
    fetchPostData () {
      Loading.show()
      axios.get(process.env.API + 'posts/id/' + this.$route.params.id)
      .then(response => {
        console.log(response)
        this.post = response.data
        this.image = this.post.image ? process.env.BASE_URL + 'uploads/' + this.post.image : false
      })
      .catch(error => {
        console.log(error)
      })
      Loading.hide()
    },
    imageSelected (image) {
      this.post.image_id = image.id
      this.image = image.link
      this.$refs.ImageModal.toggleModal()
    },
    updateIt () {
      Loading.show()
      axios({
        method: 'post',
        url: process.env.API + 'posts/update',
        data: this.post
      })
      .then(response => {
        console.log(response)
        if (response.data.status === 'ERROR') {
          Toast.create.negative({html: response.data.message})
        }
        else {
          Toast.create.positive({html: response.data.message})
        }
      })
      .catch(error => {
        console.log(error)
      })
      Loading.hide()
    },
    remove () {
      let vm = this
      Dialog.create({
        title: 'Delete Post: ' + this.post.title,
        message: 'This action is irreversible. Are you sure you want to do this?',
        buttons: [
          {
            label: 'No',
            handler () {
              console.log('Disagreed...')
            }
          },
          {
            label: 'Yes',
            handler () {
              console.log('Agreed!')
              console.log(vm.$route.params.id)
              axios({
                method: 'post',
                url: process.env.API + 'posts/delete',
                data: vm.post
              }).then(response => {
                console.log(response.data)
                if (response.data.status === 'SUCCESS') {
                  Toast.create.positive({html: response.data.message})
                  vm.$router.push({ name: 'AdminListPosts' })
                }
                else {
                  Toast.create.negative({html: response.data.message})
                }
              }).catch(error => { console.log(error) })
            }
          }
        ]
      })
    }
  }
}
</script>

<style scoped>
  .form-input {
    margin-top: 20px;
  }
</style>

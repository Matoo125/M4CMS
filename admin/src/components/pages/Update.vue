<template>
  <div class="card">
    <div class="card-title">
      Page Editor
    </div>
    <div class="card-content generic-margin">
      <div class="form-input">
        <div class="floating-label">
          <input required class="full-width" v-model="page.title">
          <label>Title</label>
        </div>
      </div>
      
      <div class="form-input">
        <div class="stacked-label">
          <textarea required class="full-width" v-model="page.description"></textarea>
          <label>Description</label>
        </div>
      </div>

      <div class="form-input">
        <label for="">Content</label>
          <editor :content="page.content" @contentChange="value => { page.content = value }"></editor>
      </div>
      <br>
      
      <div class="row wrap justify-between">
        <div class="list no-border">
          <div class="item"><div class="item-content">
            Published:   <q-toggle v-model="page.is_published"></q-toggle></div></div>
          <div class="item"><div class="item-content">Created at: {{ page.created_at }}</div></div>
          <div class="item"><div class="item-content">Updated at: {{ page.updated_at }}</div></div>
        </div>

        <div>
          <div>
            <button class="button primary" @click="$refs.ImageModal.toggleModal()">Image</button>
          </div>
          <br>
          <div v-if="page.image">
             <img style="max-width: 100%" :src="image" alt="">
          </div>
        </div>


        <div>
          <button class="primary" @click="updateIt()">Update</button>
          <button class="red">Delete</button>
        </div>
        
      </div>

    </div>

    <ImageModal ref="ImageModal" @imageSelected="imageSelected"></ImageModal>


  </div>
</template>

<script>
import axios from 'axios'
import Editor from '../Editor.vue'
import ImageModal from '../modals/Image.vue'

import { Toast, Loading } from 'quasar'
export default {
  components: { Editor, ImageModal },
  data () {
    return {
      page: { is_published: false },
      image: null
    }
  },
  created () {
    this.fetchPageData()
  },
  computed: {
  },
  methods: {
    fetchPageData () {
      Loading.show()
      axios.get(process.env.API + 'pages/id/' + this.$route.params.id)
      .then(response => {
        console.log(response)
        this.page = response.data
        this.image = parseInt(this.page.image) !== 0 ? process.env.BASE_URL + 'uploads/' + this.page.image : false
      })
      .catch(error => {
        console.log(error)
      })
      Loading.hide()
    },
    updateIt () {
      Loading.show()
      axios({
        method: 'post',
        url: process.env.API + 'pages/update',
        data: this.page
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
    imageSelected (image) {
      this.page.image_id = image.id
      this.image = image.link
      this.$refs.ImageModal.toggleModal()
    }
  }
}
</script>

<style scoped>
  .form-input {
    margin-top: 20px;
  }
</style>

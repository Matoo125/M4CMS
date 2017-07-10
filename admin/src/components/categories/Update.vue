<template>
  <div class="card" v-if="category">
    <div class="card-title">
      Category Editor
    </div>
    <div class="card-content generic-margin">
      <div class="form-input">
        <div class="floating-label">
          <input required class="full-width" v-model="category.title">
          <label>Title</label>
        </div>
      </div>
      
      <div class="form-input">
        <div class="stacked-label">
          <textarea required class="full-width" v-model="category.description"></textarea>
          <label>Description</label>
        </div>
      </div>
      
      <div class="row justify-between">
        <div class="list no-border">
          <div class="item"><div class="item-content">Created at: {{ category.created_at }}</div></div>
          <div class="item"><div class="item-content">Updated at: {{ category.updated_at }}</div></div>
        </div>

      <div class="form-input">
        <p class="caption">Belongs to: </p>
          <q-select
            v-if="pages"
            type="radio"
            v-model="category.page_id"
            :options="pages"
          ></q-select>
        </div>

        <div>
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
import ImageModal from '../modals/Image.vue'

export default {
  data () {
    return {
      image: null,
      category: {
        image_id: null
      },
      pages: null
    }
  },
  components: { ImageModal },
  created () {
    this.fetchCategoryData()
    this.fetchPagesList()
  },
  methods: {
    fetchPagesList () {
      axios.get(process.env.API + 'pages/listbasic')
      .then(response => {
        this.pages = response.data
      })
      .catch(error => {
        console.log(error)
      })
    },
    fetchCategoryData () {
      axios.get(process.env.API + 'categories/id/' + this.$route.params.id)
      .then(response => {
        console.log(response)
        this.category = response.data
        this.image = this.category.image ? process.env.BASE_URL + 'uploads/' + this.category.image : false
      })
      .catch(error => {
        console.log(error)
      })
    },
    imageSelected (image) {
      this.category.image_id = image.id
      this.image = image.link
      this.$refs.ImageModal.toggleModal()
    },
    updateIt () {
      Loading.show()
      axios({
        method: 'post',
        url: process.env.API + 'categories/update',
        data: this.category
      })
      .then(response => {
        console.log(response)
        if (response.data.status === 'ERROR') {
          Toast.create.negative({html: response.data.message})
        }
        else {
          Toast.create.positive({html: response.data.message})
        }
        Loading.hide()
      })
      .catch(error => {
        console.log(error)
      })
    },
    remove () {
      let vm = this
      Dialog.create({
        title: 'Delete Category: ' + this.category.title,
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
              axios({
                method: 'post',
                url: process.env.API + 'categories/delete',
                data: vm.category
              }).then(response => {
                console.log(response.data)
                if (response.data.status === 'SUCCESS') {
                  Toast.create.positive({html: response.data.message})
                  vm.$router.push({ name: 'AdminListCategories' })
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

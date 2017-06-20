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
        <div class="stacked-label">
          <textarea required class="full-width" v-model="page.content"></textarea>
          <label>Content</label>
        </div>
      </div>
      <br>
      
      <div class="row justify-between">
        <div class="list no-border">
          <div class="item"><div class="item-content">
            Published:   <q-toggle v-model="page.is_published"></q-toggle></div></div>
          <div class="item"><div class="item-content">Created at: {{ page.created_at }}</div></div>
          <div class="item"><div class="item-content">Updated at: {{ page.updated_at }}</div></div>
        </div>

        <div>
          <button class="primary" @click="updateIt()">Update</button>
          <button class="red">Delete</button>
        </div>
        
      </div>

    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { Toast, Loading } from 'quasar'
export default {
  data () {
    return {
      page: { is_published: false }
    }
  },
  created () {
    this.fetchPageData()
  },
  methods: {
    fetchPageData () {
      Loading.show()
      axios.get(process.env.API + 'pages/id/' + this.$route.params.id)
      .then(response => {
        console.log(response)
        this.page = response.data
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
    }
  }
}
</script>

<style scoped>
  .form-input {
    margin-top: 20px;
  }
</style>

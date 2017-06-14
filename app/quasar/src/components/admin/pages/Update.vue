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
          <button class="primary">Update</button>
          <button class="red">Delete</button>
        </div>
        
      </div>

    </div>
  </div>
</template>

<script>
import axios from 'axios'
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
      axios.get('http://m4cms.dev/admin/pages/id/' + this.$route.params.id)
      .then(response => {
        console.log(response)
        this.page = response.data
      })
      .catch(error => {
        console.log(error)
      })
    }
  },
  update () {
    axios({
      url: 'http://m4cms.dev/admin/pages/update'
    })
  }
}
</script>

<style scoped>
  .form-input {
    margin-top: 20px;
  }
</style>

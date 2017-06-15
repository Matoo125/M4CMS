<template>
  <div class="card">
    <div class="card-title">
      Create Page
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
        </div>

        <div>
          <button class="primary">Create</button>
        </div>
        
      </div>

    </div>
  </div>
</template>

<script>
// import axios from 'axios'
export default {
  data () {
    return {
      page: {
        content: '',
        description: '',
        title: '',
        is_published: false
      }
    }
  },
  methods: {
  }
}
</script>

<style scoped>
  .form-input {
    margin-top: 20px;
  }
</style>

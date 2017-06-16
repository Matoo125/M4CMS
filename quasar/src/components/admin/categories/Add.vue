<template>
  <div class="card" v-if="category">
    <div class="card-title">
      Create Category
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
          <button class="primary" @click="create()">Create</button>
        </div>
        
      </div>

    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { Toast } from 'quasar'
export default {
  data () {
    return {
      category: {
        title: '',
        description: '',
        page_id: 0
      },
      pages: null
    }
  },
  created () {
    this.fetchPagesList()
  },
  methods: {
    fetchPagesList () {
      axios.get('http://m4cms.dev/admin/pages/listbasic')
      .then(response => {
        this.pages = response.data
      })
      .catch(error => {
        console.log(error)
      })
    },
    create () {
      axios({
        method: 'post',
        url: 'http://m4cms.dev/admin/categories/create',
        data: this.category
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
    }
  }
}
</script>

<style scoped>
  .form-input {
    margin-top: 20px;
  }
</style>

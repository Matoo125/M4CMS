<template>
  <div class="card">
    <div class="card-title">
      User Editor
    </div>
    <div class="card-content generic-margin">
      <div class="form-input">
        <div class="stacked-label">
          <input class="full-width" v-model="user.username">
          <label>Username</label>
        </div>
      </div>

      <div class="form-input">
        <div class="stacked-label">
          <input type="email" class="full-width" v-model="user.email">
          <label>Email</label>
        </div>
      </div>

      <div class="form-input">
        <div class="stacked-label">
          <input class="full-width" v-model="user.first_name">
          <label>First Name</label>
        </div>
      </div>

      <div class="form-input">
        <div class="stacked-label">
          <input class="full-width" v-model="user.last_name">
          <label>Last Name</label>
        </div>
      </div>

      <div class="form-input">
        <div class="stacked-label">
          <textarea class="full-width" v-model="user.about_me"></textarea>
          <label>About Me</label>
        </div>
      </div>
      

      <br>
      
      <div class="row justify-between">
        <div class="list no-border">
          <div class="item"><div class="item-content">Created at: {{ user.created_at }}</div></div>
          <div class="item"><div class="item-content">Updated at: {{ user.updated_at }}</div></div>
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
      user: false
    }
  },
  created () {
    this.fetchUserData()
  },
  methods: {
    fetchUserData () {
      Loading.show()
      axios.get(process.env.API + 'users/id/' + this.$route.params.id)
        .then(response => {
          console.log(response)
          this.user = response.data
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
        url: process.env.API + 'users/update',
        data: this.user
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

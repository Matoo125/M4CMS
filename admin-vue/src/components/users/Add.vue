<template>
  <div class="card">
    <div class="card-title">
      Create User
    </div>
    <div class="card-content generic-margin">

    <div class="form-input">
      <div class="floating-label">
        <input type="text" class="full-width" v-model="user.username">
        <label>Username</label>
      </div>
    </div>

    <div class="form-input">
      <div class="floating-label">
        <input type="email" class="full-width" v-model="user.email">
        <label>Email</label>
      </div>
    </div>

    <div class="form-input">
      <div class="floating-label">
        <input type="password" class="full-width" v-model="user.password">
        <label>Password</label>
      </div>
    </div>
      

      <br>
      
      <div class="row justify-between">
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

export default {
  data () {
    return {
      user: {
        role: 1
      }
    }
  },
  methods: {
    create () {
      Loading.show()
      axios({
        method: 'post',
        url: process.env.API + 'users/create',
        data: this.user
      })
        .then(response => {
          console.log(response.data)
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

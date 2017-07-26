<template>
  <div class="login-parent">
    <div class="login card generic-margin has-text-centered">
      <div class="card-title">
        Login
      </div>
      <div class="card-content">
          <label>Email</label>
          <input type="email" required class="full-width" v-model="email">

          <label>Password</label>
          <input type="password" required class="full-width" v-model="password">
      </div>

      <button class="primary" @click="login()">
        Login
      </button>

    </div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'Login',
  data () {
    return {
      email: null,
      password: null
    }
  },
  created () {
    this.$store.dispatch('checkSession')
  },
  methods: {
    login () {
      axios({
        method: 'post',
        url: process.env.API + 'users/login',
        withCredentials: true,
        data: {
          email: this.email,
          password: this.password
        }
      })
      .then(response => {
        if (response.data.status === 'SUCCESS') {
          this.$store.commit('setSession', true)
          this.$router.push({ name: 'AdminHome' })
        }
        console.log(response.data)
      }).catch(error => {
        // console.log(JSON.stringify(error))
        if (error.response) {
          console.log('error response')
          console.log(error.response)
        }
      })
    }
  }
}
</script>

<style lang="sass">

html, body, #q-app, .login-parent
  height: 100%

.login-parent
  display: flex;
  justify-content: center; /* align horizontal */
  align-items: center; /* align vertical */
  height: 100%
  background-color: #03a9f4

.login
  background-color: #f2f2f2
  max-width: 500px

</style>

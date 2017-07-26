import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)

const store = new Vuex.Store({
  state: {
    user: {
      session: false
    }
  },
  actions: {
    checkSession (context) {
      axios({
        method: 'get',
        url: process.env.API + 'users/is_logged_in',
        withCredentials: true
      })
      .then(response => {
        let bool = response.data.status === 'SUCCESS'
        context.commit('setSession', bool)
      }).catch(error => {
        if (error.response) {
          console.log(error.response)
        }
      })
    },
    logout (context) {
      axios({
        method: 'get',
        url: process.env.API + 'users/logout',
        withCredentials: true
      })
      .then(response => {
        context.commit('setSession', false)
      }).catch(error => { console.log(error) })
    }
  },
  mutations: {
    setSession (state, bool) {
      state.user.session = bool
    }
  }
})

export default store

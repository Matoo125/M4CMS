import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
Vue.use(Vuex)
const store = new Vuex.Store({
  state: {
    pages: null
  },
  actions: {
    getListOfPages (context) {
      axios({
        url: process.env.API + '/pages/listBasic',
        method: 'get'
      })
      .then(response => {
        console.log(response)
        context.commit('setPages', response.data)
      })
      .catch(error => {
        console.log(error)
      })
    }
  },
  mutations: {
    setPages (state, pages) {
      state.pages = pages
    }
  },
  getters: {
  },
  modules: {
  }
})
export default store

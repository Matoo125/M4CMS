<template>
  <div>

  <div class="card-content" v-if="settings">

    <div class="form-input">
        <label>Website Title</label>
        <input class="full-width" v-model="settings.title.value">
    </div>

    <div class="form-input">
        <label>Website Description</label>
        <input class="full-width" v-model="settings.description.value">
    </div>

    <div class="form-input">
       <label >Tags</label>
       <q-chips v-model="tags"></q-chips>
    </div>

    <br>
    <div>
      Published:   <q-toggle v-model="isOnline"></q-toggle>
    </div>
    <br>
    <button class="primary" @click="update()">Update</button>



  </div>

  </div>
</template>

<script>
import axios from 'axios'
import { Toast } from 'quasar'

export default {
  data () {
    return {
      settings: null
    }
  },
  created () {
    this.load()
  },
  computed: {
    isOnline: {
      get () {
        return this.settings.online.value === 'true'
      },
      set (value) {
        this.settings.online.value = value ? 'true' : 'false'
      }
    },
    tags: {
      get () {
        return JSON.parse(this.settings.tags.value)
      },
      set (value) {
        this.settings.tags.value = JSON.stringify(value)
      }
    }
  },
  methods: {
    load () {
      axios.get(process.env.API + 'settings/load')
      .then(response => {
        console.log(response)
        this.settings = response.data
      }).catch(error => { console.log(error) })
    },
    update () {
      axios({
        method: 'post',
        url: process.env.API + 'settings/update',
        data: {
          title: this.settings.title.value,
          description: this.settings.description.value,
          tags: this.settings.tags.value,
          online: this.settings.online.value
        }
      })
      .then(response => {
        if (response.data.status === 'SUCCESS') {
          Toast.create.positive({html: response.data.message})
        }
      }).catch(error => { console.log(error) })
    }
  }
}
</script>

<style scoped>
  .form-input {
    margin-top: 20px;
  }
</style>

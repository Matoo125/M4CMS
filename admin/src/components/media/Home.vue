<template>
  <div class="layout-padding " v-if="media">

    <div class="card">
      <div class="card-title">Media Gallery</div>
      <div class="card-content">
            <q-gallery :src="gallery"></q-gallery>
      </div>
      <div class="card-footer">
        <q-uploader :url="url + 'media/create'"></q-uploader>
      </div>
    </div>


  </div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'Home',
  data () {
    return {
      media: null,
      url: process.env.API
    }
  },
  created () {
    this.getMedia()
  },
  beforeDestroy () {

  },
  computed: {
    gallery () {
      let gallery = []
      for (let item of this.media) {
        gallery.push('http://localhost/M4CMS/public/uploads/' + item.folder + '/' + item.filename)
      }
      return gallery
    }
  },
  methods: {
    getMedia () {
      axios.get(process.env.API + 'media/list')
      .then(response => {
        console.log(response)
        this.media = response.data
      }).catch(error => console.log(error))
    }
  }
}
</script>

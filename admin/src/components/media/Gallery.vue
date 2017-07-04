<template>
  <div v-if="media">
    <div class="q-gallery">
      <div v-for="image in gallery">
        <div><img :src="image" alt="" @click="emitLink(image)"></div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'MediaGallery',
  data () {
    return {
      media: null
    }
  },
  created () {
    this.getMedia()
  },
  computed: {
    gallery () {
      let gallery = []
      for (let item of this.media) {
        gallery.push(process.env.BASE_URL + '/uploads/' + item.folder + '/' + item.filename)
      }
      return gallery
    }
  },
  methods: {
    emitLink (image) {
      this.$emit('selected', image)
    },
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

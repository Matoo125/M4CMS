<template>
  <div class="layout-padding ">

    <div class="card">
      <div class="card-title">Media Gallery</div>
      <div class="card-content">
            <MediaGallery></MediaGallery>
      </div>
      <div class="card-footer">
        <q-uploader :url="url + 'media/create'"></q-uploader>
      </div>
    </div>


  </div>
</template>

<script>
import MediaGallery from './Gallery.vue'
export default {
  name: 'Home',
  components: { MediaGallery },
  data () {
    return {
      url: process.env.API
    }
  },
  created () {

  },
  beforeDestroy () {

  },
  computed: {

  },
  methods: {

  }
}
</script>

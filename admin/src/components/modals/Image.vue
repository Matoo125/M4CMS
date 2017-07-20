<template>
  <q-modal ref="imageModal" class="justified" content-css="padding: 20px; min-width: 500px;">
    <h4>Add Image</h4>
    <q-tabs
      :refs="$refs"
      default-tab="tab-1"
      style="margin-bottom: 25px;"
    >
      <q-tab name="tab-1" icon="file_upload">
        Upload 
      </q-tab>
      <q-tab name="tab-2" icon="link">
        From URL
      </q-tab>
      <q-tab name="tab-3" icon="perm_media">
        Media
      </q-tab>
   
    </q-tabs>
    <!-- Targets -->
    <div ref="tab-1">
      <q-uploader @upload="upload" :url="url"></q-uploader>
    </div>
    <div ref="tab-2">
      <div class="stacked-label">
        <input v-model="image.link" class="full-width">
        <label>Insert URL</label>
      </div>
      <button class="primary" @click="insertUrl()">
        Submit
      </button>
    </div>
    <div ref="tab-3">
      <Gallery @selected="selectedFromGallery"></Gallery>
    </div>

  </q-modal>
</template>

<script>
import Gallery from '../media/Gallery.vue'

export default {
  name: 'ImageModal',
  components: { Gallery },
  props: ['active'],
  data () {
    return {
      url: process.env.API + 'media/create',
      image: {
        link: null
      }
    }
  },
  methods: {
    selectedFromGallery (image) {
      this.$emit('imageSelected', image)
    },
    insertUrl () {
      this.$emit('imageSelected', this.image)
    },
    upload (event, xhr) {
      var response = JSON.parse(xhr)
      var uploadedImageUrl = process.env.UPLOADS + response.filename
      this.$emit('imageSelected', {link: uploadedImageUrl, id: response.id})
    },
    toggleModal () {
      this.$refs.imageModal.toggle()
    }
  }
}

</script>

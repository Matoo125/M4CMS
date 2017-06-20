<template>
  <div>

  <div class="card-content">

    <div class="form-input">
        <label>Website Title</label>
        <input class="full-width" v-model="title">
    </div>

    <div class="form-input">
        <label>Website Description</label>
        <input class="full-width" v-model="title">
    </div>

    <div class="form-input">
       <label >Tags</label>
       <q-chips v-model="tags"></q-chips>
    </div>

    <br>
    <div>
      Published:   <q-toggle v-model="published"></q-toggle>
    </div>
    <br>
    <button class="primary" @click="update()">Update</button>



  </div>

  </div>
</template>

<script>
export default {
  data () {
    return {
      title: '',
      tags: [],
      description: '',
      published: true
    }
  },
  methods: {
    update () {
      console.log('preform update')
    }
  }
}
</script>

<style scoped>
  .form-input {
    margin-top: 20px;
  }
</style>

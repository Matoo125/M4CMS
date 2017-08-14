<template>
  <div class="layout-view">

    <div class="row" v-if="statistics">
      <div class="card">
        <div class="card-title bg-primary text-white">
          Number of Pages
        </div>
        <div class="card-content card-force-top-padding">
          {{ statistics.numberOfPages }}
        </div>
      </div>

      <div class="card">
        <div class="card-title bg-primary text-white">
          Number of Categories
        </div>
        <div class="card-content card-force-top-padding">
          {{ statistics.numberOfCategories }}
        </div>
      </div>

      <div class="card">
        <div class="card-title bg-primary text-white">
          Number of Posts
        </div>
        <div class="card-content card-force-top-padding">
          {{ statistics.numberOfPosts }}
        </div>
      </div>

      <div class="card">
        <div class="card-title bg-primary text-white">
          Number of Users
        </div>
        <div class="card-content card-force-top-padding">
          {{ statistics.numberOfUsers }}
        </div>
      </div>
    </div>

  </div>
</template>
<script>
import axios from 'axios'
export default{
  data () {
    return {
      statistics: null,
      loading: false
    }
  },
  mounted () {
    this.fetchDashboardData()
  },
  methods: {
    fetchDashboardData () {
      this.loading = true
      axios.get(process.env.API + 'index')
        .then(response => {
          console.log(response)
          this.statistics = response.data
          this.loading = false
        })
        .catch(error => {
          console.log(error)
        })
    }
  }
}
</script>

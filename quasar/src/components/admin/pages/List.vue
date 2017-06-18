<template>
  <div class="row" v-if="pages">
  <table class="q-table loose striped-odd bordered cell-delimiter highlight">
    <thead>
      <tr>
        <th class="text-left">Title</th>
        <th class="text-right">Description</th>
        <th class="text-right">Published</th>
        <th class="text-right">Last update</th>
      </tr>
    </thead>
    <tbody>
      <router-link tag="tr" :to="'/admin/page/' + page.id" v-for="page in pages" :key="page.id">
        <td class="text-left">{{ page.title }}</td>
        <td class="text-right">{{ page.description }}</td>
        <td class="text-right">{{ page.is_published }}</td>
        <td class="text-right">{{ page.updated_at }}</td>
      </router-link>
    </tbody>
  </table>
  <router-link
    tag="button"
    class="primary circular absolute-bottom-right"
    style="right: 18px; bottom: 18px; font-size: 2em"
    to="page/create"
  >+</router-link>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  mounted () {
    this.fetchPagesListData()
  },
  data () {
    return {
      pages: null
    }
  },
  methods: {
    fetchPagesListData () {
      axios.get(process.env.api + 'pages/list')
      .then(response => {
        console.log(response)
        this.pages = response.data
      })
      .catch(error => {
        console.log(error)
      })
    },
    create () {
      this.$router.push({ path: 'page/create' })
    }
  }

}
</script>

<style scoped>
  tbody, tbody > tr > td {
    cursor: pointer;
  }
</style>

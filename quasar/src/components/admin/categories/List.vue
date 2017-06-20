<template>
  <div class="row" v-if="categories">
  <table class="q-table loose striped-odd bordered cell-delimiter highlight">
    <thead>
      <tr>
        <th class="text-left">Title</th>
        <th class="text-right">Description</th>
        <th class="text-right">Last update</th>
      </tr>
    </thead>
    <tbody>
      <router-link tag="tr" :to="'/admin/category/' + category.id" v-for="category in categories" :key="category.id">
        <td class="text-left">{{ category.title }}</td>
        <td class="text-right">{{ category.description }}</td>
        <td class="text-right">{{ category.updated_at }}</td>
      </router-link>
    </tbody>
  </table>

  <router-link
    tag="button"
    class="primary circular absolute-bottom-right"
    style="right: 18px; bottom: 18px; font-size: 2em"
    to="category/create"
  >+</router-link>

  </div>
</template>

<script>
import axios from 'axios'
import { Loading } from 'quasar'
export default {
  mounted () {
    this.fetchCategoriesListData()
  },
  data () {
    return {
      categories: null
    }
  },
  methods: {
    fetchCategoriesListData () {
      Loading.show()
      axios.get(process.env.API + 'categories/list')
      .then(response => {
        console.log(response)
        this.categories = response.data
      })
      .catch(error => {
        console.log(error)
      })
      Loading.hide()
    }
  }

}
</script>

<style scoped>
  tbody, tbody > tr > td {
    cursor: pointer;
  }
</style>

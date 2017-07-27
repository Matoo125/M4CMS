<template>
  <div class="row" v-if="users">
  <table class="q-table loose striped-odd bordered cell-delimiter highlight">
    <thead>
      <tr>
        <th class="text-left">#</th>
        <th class="text-right">Username</th>
        <th class="text-right">Email</th>
        <th class="text-right">First Name</th>
        <th class="text-right">Last Name</th>
        <th class="text-right">About me</th>
        <th class="text-right">Role</th>
        <th class="text-right">Image #</th>
        <th class="text-right">Created At</th>
        <th class="text-right">Updated At</th>
      </tr>
    </thead>
    <tbody>
      <router-link tag="tr" :to="'/user/' + user.id" v-for="user in users" :key="user.id">
        <td class="text-left">{{ user.id }}</td>
        <td class="text-right">{{ user.username }}</td>
        <td class="text-right">{{ user.email }}</td>
        <td class="text-right">{{ user.first_name }}</td>
        <td class="text-right">{{ user.last_name }}</td>
        <td class="text-right">{{ user.about_me }}</td>
        <td class="text-right">{{ user.role }}</td>
        <td class="text-right">{{ user.image }}</td>
        <td class="text-right">{{ moment(user.created_at) }}</td>
        <td class="text-right">{{ moment(user.updated_at) }}</td>
      </router-link>
    </tbody>
  </table>
  <router-link
    tag="button"
    class="primary circular absolute-bottom-right"
    style="right: 18px; bottom: 18px; font-size: 2em"
    to="user/create"
  >+</router-link>
  </div>
</template>

<script>
import { Loading } from 'quasar'
import axios from 'axios'
import moment from 'moment'
export default {
  mounted () {
    Loading.show()
    this.fetchUsersListData()
  },
  data () {
    return {
      users: null
    }
  },
  methods: {
    fetchUsersListData () {
      axios.get(process.env.API + 'users/list')
        .then(response => {
          console.log(response)
          this.users = response.data
          Loading.hide()
        })
        .catch(error => {
          console.log(error)
        })
    },
    create () {
      this.$router.push({ path: 'user/create' })
    },
    moment (date) {
      return moment(date).format('MMMM Do YYYY')
    }
  }

}
</script>

<style scoped>
  tbody, tbody > tr > td {
    cursor: pointer;
  }
</style>

<template>
  <nav class="nav has-shadow">
    <div class="container">
      <div class="nav-left">
        <router-link class="nav-item logo" to="/" replace>
          M4CMS
        </router-link>
      </div>
      <span :class="[{'is-active': activeMobileMenu}, 'nav-toggle']" @click="toggleMenu">
        <span></span>
        <span></span>
        <span></span>
      </span>
      <div :class="[{'is-active': activeMobileMenu}, 'nav-right nav-menu']">
        <router-link class="nav-item" v-for="page in pages" :key="page.value" :to="{ name: 'Page', params: { id: page.value }}">
          {{ page.label }}
        </router-link>


      </div>
    </div>
  </nav>
</template>

<script>
export default {
  name: 'navbar',
  data () {
    return {
      activeMobileMenu: false
    }
  },
  methods: {
    toggleMenu () {
      this.activeMobileMenu = !this.activeMobileMenu
    }
  },
  computed: {
    pages () {
      return this.$store.state.pages
    }
  }
}
</script>

<style>
  .logo {
    color: #7a7a7a !important;
  }
</style>

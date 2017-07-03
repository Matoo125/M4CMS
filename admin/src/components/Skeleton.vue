<template>
<q-layout>
  <div slot="header" class="toolbar light-green">
    <!-- opens drawer using its ref -->
    <button
      class="hide-on-drawer-visible"
      @click="$refs.drawer.open()"
    >
      <i>menu</i>
    </button>
    <q-toolbar-title :padding="2">
      M4CMS
    </q-toolbar-title>
    <button>
      <i>mail</i>
    </button>
    <button>
      <i>alarm</i>
    </button>
  </div>
    <q-drawer ref="drawer">
    <div class="list platform-delimiter">
      <q-drawer-link icon="dashboard" to="/">Dashboard</q-drawer-link>
      <q-drawer-link icon="tab" to="/pages">Pages</q-drawer-link>
      <q-drawer-link icon="folder" to="/categories">Categories</q-drawer-link>
      <q-drawer-link icon="subject" to="/posts">Posts</q-drawer-link>
      <q-drawer-link icon="accessibility" to="/users">Users</q-drawer-link>
      <q-drawer-link icon="image" to="/media">Media</q-drawer-link>
      <q-drawer-link icon="settings" to="/settings">Settings</q-drawer-link>
    </div>
  </q-drawer>
  <router-view class="layout-view" ></router-view>
</q-layout>
</template>

<script>
  export default {
    name: 'AdminDrawer',
    data () {
      return {
        nothing: ''
      }
    }
  }
</script>

<style>
.list > .item:first-child, .list > .q-collapsible:first-child {
  margin-top: 0;
}  
.drawer .list {
  width: 100%;
  border: 0;
  border-bottom: 1px solid #e0e0e0;
}
</style>

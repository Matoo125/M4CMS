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
      <q-drawer-link icon="dashboard" to="/account">Dashboard</q-drawer-link>
      <q-drawer-link icon="tab" to="/alarms">Pages</q-drawer-link>
      <q-drawer-link icon="folder" to="/alarms">Categories</q-drawer-link>
      <q-drawer-link icon="subject" to="/alarms">Posts</q-drawer-link>
      <q-drawer-link icon="accessibility" to="/alarms">Users</q-drawer-link>
      <q-drawer-link icon="settings" to="/alarms">Settings</q-drawer-link>
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

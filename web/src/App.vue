<template>
  <div id="app">
  <navbar></navbar>
    <router-view></router-view>
  </div>
</template>

<script>
import navbar from './components/Navbar.vue'
export default {
  name: 'app',
  components: { navbar },
  created () {
    this.$store.dispatch('getListOfPages')
  }
}
</script>

<style lang="scss">
  // Import Bulma's core
  @import "~bulma/sass/utilities/_all";
  // Set your colors
  $primary: #f1896f;
  $primary-invert: findColorInvert($primary);
  $twitter: #4099ff;
  $twitter-invert: findColorInvert($twitter);
  // Setup $colors to use as bulma classes (e.g. 'is-twitter')
  $colors: (
      "white": ($white, $black),
      "black": ($black, $white),
      "light": ($light, $light-invert),
      "dark": ($dark, $dark-invert),
      "primary": ($primary, $primary-invert),
      "info": ($info, $info-invert),
      "success": ($success, $success-invert),
      "warning": ($warning, $warning-invert),
      "danger": ($danger, $danger-invert),
      "twitter": ($twitter, $twitter-invert)
  );
  // Links
  $link: $primary;
  $link-invert: $primary-invert;
  $link-focus-border: $primary;
  // Import Bulma and Buefy styles
  @import "~bulma";
  @import "~buefy/src/scss/buefy";


</style>

<style>
  .nav-item a.is-active, a.nav-item.is-active {
      color:#f1896f;
  }
  
.breadcrumb {
  -webkit-touch-callout: none;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  -webkit-box-align: stretch;
      -ms-flex-align: stretch;
          align-items: stretch;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  font-size: 1rem;
  overflow: hidden;
  overflow-x: auto;
  white-space: nowrap;
}

.breadcrumb:not(:last-child) {
  margin-bottom: 1.5rem;
}

.breadcrumb a {
  -webkit-box-align: center;
      -ms-flex-align: center;
          align-items: center;
  color: #7a7a7a;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: center;
      -ms-flex-pack: center;
          justify-content: center;
  padding: 0.5em 0.75em;
}

.breadcrumb a:hover {
  color: #363636;
}

.breadcrumb li {
  -webkit-box-align: center;
      -ms-flex-align: center;
          align-items: center;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
}

.breadcrumb li.is-active a {
  color: #363636;
  cursor: default;
  pointer-events: none;
}

.breadcrumb li + li:before {
  color: #4a4a4a;
  content: '\0002f';
}

.breadcrumb ul, .breadcrumb ol {
  -webkit-box-align: center;
      -ms-flex-align: center;
          align-items: center;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-flex: 1;
      -ms-flex-positive: 1;
          flex-grow: 1;
  -ms-flex-negative: 0;
      flex-shrink: 0;
  -webkit-box-pack: start;
      -ms-flex-pack: start;
          justify-content: flex-start;
}

.breadcrumb .icon:first-child {
  margin-right: 0.5em;
}

.breadcrumb .icon:last-child {
  margin-left: 0.5em;
}

.breadcrumb.is-centered ol, .breadcrumb.is-centered ul {
  -webkit-box-pack: center;
      -ms-flex-pack: center;
          justify-content: center;
}

.breadcrumb.is-right ol, .breadcrumb.is-right ul {
  -webkit-box-pack: end;
      -ms-flex-pack: end;
          justify-content: flex-end;
}

.breadcrumb.is-small {
  font-size: 0.75rem;
}

.breadcrumb.is-medium {
  font-size: 1.25rem;
}

.breadcrumb.is-large {
  font-size: 1.5rem;
}

.breadcrumb.has-arrow-separator li + li:before {
  content: '\02192';
}

.breadcrumb.has-bullet-separator li + li:before {
  content: '\02022';
}

.breadcrumb.has-dot-separator li + li:before {
  content: '\000b7';
}

.breadcrumb.has-succeeds-separator li + li:before {
  content: '\0227B';
}
</style>

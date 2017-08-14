<template>
  <q-modal ref="basicModal">
    <h4>Navigation</h4>

    <div class="row">
      <ul v-if="navigation">
        <li v-for="n in navigation"> {{ n.text }} - {{ n.link }} <a @click="removeItem(n)">x</a></li>
      </ul>
    </div>

    <hr>

    <div class="row">
      <div>
        <input type="text" v-model="newItem.text" placeholder="Text">
        <input type="text" v-model="newItem.link" placeholder="Link">        
      </div>
      
      <div>
        <button class="primary" @click="addItem">Add</button>
      </div>
    </div>

    <hr>

    <div class="row">
      <button class="sucess" @click="saveNav()">Save</button>
      <button class="secondary" @click="$refs.basicModal.close()">Close</button>
    </div>

  </q-modal>
</template>

<script>
export default {
  name: 'Navigation',
  props: ['current'],
  mounted () {
    this.navigation = JSON.parse(this.current)
    this.$on('open', () => { this.$refs.basicModal.open() })
  },
  data () {
    return {
      newItem: {
        text: null,
        link: null
      },
      navigation: []
    }
  },
  methods: {
    addItem () {
      this.navigation.push({text: this.newItem.text, link: this.newItem.link})
      this.newItem.text = null
      this.newItem.link = ''
    },
    saveNav () {
      this.$emit('saveNav', this.navigation)
    },
    removeItem (item) {
      var index = this.navigation.indexOf(item)
      this.navigation.splice(index, 1)
    }
  }
}
</script>

<style>
  
</style>

<template>
  <div>
    <quill-editor v-model="eContent"
                  ref="myQuillEditor"
                  :options="editorOption"
                  @blur="onEditorBlur($event)"
                  @focus="onEditorFocus($event)"
                  @ready="onEditorReady($event)">
    </quill-editor>
    
    

    <q-modal ref="imageModal" content-css="padding: 50px;">
      <h4>Add Image</h4>
      <q-tabs
        :refs="$refs"
        default-tab="tab-4"
      >
        <q-tab name="tab-1" icon="message">
          Tab 1
        </q-tab>
        <q-tab name="tab-2" disable icon="fingerprint">
          Tab 2
        </q-tab>
        <q-tab name="tab-3" icon="alarm">
          Tab 3
        </q-tab>
        <q-tab name="tab-4" icon="accessibility">
          Tab 4
        </q-tab>
        <q-tab name="tab-5" hidden icon="accessibility">
          Tab 5
        </q-tab>
      </q-tabs>
      <!-- Targets -->
      <div ref="tab-1">...</div>
      <div ref="tab-2">...</div>
      <div ref="tab-3">...</div>
      <div ref="tab-4">...</div>
      <div ref="tab-5">...</div>
      <button class="primary" @click="$refs.imageModal.close()">Close</button>
    </q-modal>
  </div>
</template>

<script>
import { quillEditor } from 'vue-quill-editor'

export default {
  components: { quillEditor },
  name: 'Editor',

  data () {
    return {
      editorOption: {
        modules: {
          toolbar: {
            container: [
              ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
              ['link', 'blockquote', 'code-block', 'image'],

              [{ 'list': 'ordered' }, { 'list': 'bullet' }],
              [{ 'indent': '-1' }, { 'indent': '+1' }],          // outdent/indent

              [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

              [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
              [{ 'font': [] }],
              [{ 'align': [] }],

              ['clean']                                       // remove formatting button
            ],
            handlers: {
              'image': this.imageHandler
            }
          }
        }
      }
    }
  },
  props: ['content'],
  computed: {
    editor () {
      return this.$refs.myQuillEditor.quill
    },
    eContent: {
      get: function () {
        return this.content
      },
      set: function (value) {
        this.$emit('contentChange', value)
      }
    }
  },
  methods: {
    imageHandler () {
      this.$refs.imageModal.open()
      var range = this.$refs.myQuillEditor.quill.getSelection()
      // var value = prompt('What is the image URL')
      var value = ''
      this.$refs.myQuillEditor.quill.insertEmbed(range.index, 'image', value)
    },
    onEditorBlur (editor) {
      console.log('editor blur!', editor)
    },
    onEditorFocus (editor) {
      console.log('editor focus!', editor)
    },
    onEditorReady (editor) {
      console.log('editor ready!', editor)
    },
    onEditorChange ({ editor, html, text }) {
      console.log('editor change!', editor, html, text)
      this.content = html
    }
  }
}
</script>

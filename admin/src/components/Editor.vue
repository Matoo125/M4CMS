<template>
  <div>
    <quill-editor v-model="eContent"
                  ref="myQuillEditor"
                  :options="editorOption"
                  @blur="onEditorBlur($event)"
                  @focus="onEditorFocus($event)"
                  @ready="onEditorReady($event)">
    </quill-editor>
    
    
    <ImageModal @imageSelected="addImage" :active="imageModalActive" ref="imageSelectModal"></ImageModal> <!-- Modal for image choosing -->

  </div>
</template>

<script>
import { quillEditor } from 'vue-quill-editor'
import ImageModal from './modals/Image'

export default {
  components: { quillEditor, ImageModal },
  name: 'Editor',

  data () {
    return {
      imageModalActive: false,
      range: null,
      editorOption: {
        modules: {
          toolbar: {
            container: [
              ['bold', 'italic', 'underline', 'strike'], // toggled buttons
              ['link', 'blockquote', 'code-block', 'image'],

              [{ 'list': 'ordered' }, { 'list': 'bullet' }],
              [{ 'indent': '-1' }, { 'indent': '+1' }], // outdent/indent

              [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

              [{ 'color': [] }, { 'background': [] }], // dropdown with defaults from theme
              [{ 'font': [] }],
              [{ 'align': [] }],

              ['clean'] // remove formatting button
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
    addImage (image) {
      this.$refs.myQuillEditor.quill.insertEmbed(this.range.index, 'image', image.link)
      this.$refs.imageSelectModal.toggleModal()
    },
    imageHandler () {
      this.$refs.imageSelectModal.toggleModal()
      this.range = this.$refs.myQuillEditor.quill.getSelection()
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

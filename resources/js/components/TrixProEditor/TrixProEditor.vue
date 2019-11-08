<template>
  <div>
    <vue-trix
      inputId="trix"
      :inputName="name"
      v-model="editorContent"
      :placeholder="placeHolder"
      @trix-file-accept="preventAttachment"
    />
  </div>
</template>

<script>
import VueTrix from 'vue-trix';
export default {
  name: 'TrixProEditor',

  components: {
    VueTrix,
  },

  data() {
    return {
      editorContent: this.value,
      placeHolder: this.placeholder || 'Enter your content',
    };
  },

  props: ['name', 'value', 'placeholder'],

  created() {},

  methods: {
    updateEditorContent() {
      this.$emit('updateContent', this.editorContent);
    },
    preventAttachment(e) {
      e.preventDefault();
    },
  },

  watch: {
    editorContent: {
      handler: 'updateEditorContent',
    },
  },
};
</script>

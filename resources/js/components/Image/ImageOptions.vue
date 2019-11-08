<template>
  <div>
    <button type="button" class="btn btn-sm btn-secondary mr-2" @click="viewImage">
      <i class="fa fa-eye">View</i>
    </button>
    <button type="button" class="btn btn-sm btn-success mr-2" @click="editImage">
      <i class="fa fa-edit">Edit</i>
    </button>
    <button type="button" class="btn btn-sm btn-danger mr-2" @click="removeImage">
      <i class="icon-trash">Remove</i>
    </button>
    <image-view-and-edit
      v-if="openEditModal"
      :imagejson="imagejson"
      @closeModal="closeModal"
      :isEditing="isEditing"
      @getImages="getImages"
    />
  </div>
</template>

<script>
import api from '../../api';
import ImageViewAndEdit from './ImageViewAndEdit.vue';

export default {
  name: 'ImageOptions',
  components: {
    ImageViewAndEdit,
  },
  data() {
    return {
      openEditModal: false,
      isEditing: false /* to view: false, to edit: true */,
    };
  },
  props: {
    imagejson: {
      type: Object,
      default: {},
    },
  },
  methods: {
    viewImage() {
      this.isEditing = false;
      this.openEditModal = true;
    },
    closeModal() {
      this.isEditing = false;
      this.openEditModal = false;
    },
    editImage() {
      this.isEditing = true;
      this.openEditModal = true;
    },
    getImages() {
      this.$emit('getImages');
    },
    removeImage() {
      let obj = { id: this.imagejson.id };
      api
        .deleteImage(obj)
        .then(res => {
          if (res.data) alert('Image deleted successfully.');
          this.getImages();
        })
        .catch(err => console.log(err));
    },
  },
};
</script>

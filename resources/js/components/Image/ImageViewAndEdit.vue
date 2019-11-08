<template>
  <div class="modal-content">
    <div class="modal-header">
      <span class="font-size-24">{{ isEditing ? 'Edit Image' : 'View Image'}}</span>
      <span class="closeButton" @click="closeModal">&times;</span>
    </div>
    <div class="image-modal-body">
      <div class="image-modal">
        <img :src="imageSrc" class="modal-image">
        <button
          class="change-image btn btn-primary fas fa-camera"
          type="button"
          v-if="isEditing"
          @click="changeImage"
        ></button>
        
      </div>
      <!--============================================ VIEW MODE ===========================================-->
      <p v-if="!isEditing">{{imagejson.description}}</p>
      <!--============================================ EDIT MODE ===========================================-->
      <div v-else>
        <input
          v-if="isEditing"
          type="file"
          style="height:0; width:0;position: relative;"
          accept="image/*"
          @change="previewFiles"
          ref="images"
          id="image-to-upload"
        >
        <textarea placeholder="description" v-model="imagejson.description"/>
        <button class="btn btn-primary" type="button" @click="saveImage">Save</button>
      </div>
    </div>
  </div>
</template>

<script>
import api from '../../api';
export default {
  name: 'ImageViewAndEdit',
  props: {
    imagejson: {
      type: Object,
      default: {},
    },
    isEditing: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      newImage: null,
      imageSrc: '',
    };
  },
  computed: {
    imageUrl() {
      return this.imagejson.image
        ? `/images/account/${this.imagejson.image}`
        : '';
    },
  },
  mounted() {
    this.imageSrc = this.imagejson.image
      ? `/images/account/${this.imagejson.image}`
      : '';
  },
  methods: {
    previewFiles() {
      if (this.$refs.images.files && this.$refs.images.files[0]) {
        this.newImage = this.$refs.images.files[0];
        let reader = new FileReader();

        reader.onload = e => {
          this.imageSrc = e.target.result;
        };

        reader.readAsDataURL(this.$refs.images.files[0]);
      } else this.newImage = null;
    },
    closeModal() {
      this.$emit('closeModal');
    },
    getImages() {
      this.$emit('getImages');
    },
    changeImage() {
      this.$refs.images.click();
    },
    saveImage() {
      let formData = new FormData(); // instantiate it
      let { id, description } = this.imagejson;

      formData.set('id', id);
      formData.set('description', description);
      if (this.newImage) {
        formData.append('image', this.$refs.images.files[0]);
      }
      let headers = {
        'content-type': 'multipart/form-data', // do not forget this
      };
      api
        .updateDocumentDetails(formData, headers)
        .then(res => {
          this.getImages();
          this.closeModal();
        })
        .catch(err => console.log(err));
    },
  },
};
</script>

<style scoped>
textarea {
  resize: none;
  width: 90%;
  height: auto;
  margin: 9px;
}
.change-image {
  position: absolute;
  right: 10px;
  bottom: 10px;
}
.image-modal {
  position: relative;
}
.image-modal img {
  border-radius: 0;
}
/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 99; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  right: 0;
  bottom: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0, 0, 0); /* Fallback color */
  background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  position: fixed;
  background-color: #fefefe;
  margin: auto;
  z-index: 999;
  padding: 0;
  border: 1px solid #888;
  width: auto;
  max-width: 500px;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
  -webkit-animation-name: animatetop;
  -webkit-animation-duration: 0.4s;
  animation-name: animatetop;
  animation-duration: 0.4s;
}

/* Add Animation */
@-webkit-keyframes animatetop {
  from {
    top: -300px;
    opacity: 0;
  }
  to {
    top: 50%;
    opacity: 1;
  }
}

@keyframes animatetop {
  from {
    top: -300px;
    opacity: 0;
  }
  to {
    top: 50%;
    opacity: 1;
  }
}

/* The Close Button */
.closeButton {
  color: white;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.closeButton:hover,
.closeButton:focus {
  color: #fff;
  text-decoration: none;
  cursor: pointer;
}

.modal-header {
  padding: 2px 16px;
  background-color: #5cb85c;
  color: white;
}
.font-size-24 {
  font-size: 24px;
}
.image-modal-body {
  padding: 16px;
}
.modal-image {
  width: 100%;
  max-height: 350px;
}

.modal-footer {
  padding: 2px 16px;
  background-color: #5cb85c;
  color: white;
}
</style>

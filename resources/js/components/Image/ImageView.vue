<template>
  <div>
    <table class="image-table card-header" v-if="documentsArray.length>0">
      <tr class="pt-1 pb-1 bg-gray-700 theme-color">
        <th width="30%" class="text-light">
          <i class="fa fa-picture-o" aria-hidden="true"></i>
          <strong>Image</strong>
        </th>
        <th width="40%" class="text-light">
          <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
          <strong>Description</strong>
        </th>
        <th width="30%" class="text-light">
          <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
          <strong>Operations</strong>
        </th>
      </tr>
      <tr v-for="(doc, index) in documentsArray" :key="index">
        <td>
          <img :src="getUrl(doc.thumbnail)" alt="image">
        </td>
        <td>
          <p>{{ doc.description }}</p>
        </td>
        <td>
          <image-options :imagejson="doc" accounid="accounid" @getImages="getImages"/>
        </td>
      </tr>
    </table>
  </div>
</template>
<script>
import api from '../../api';
import ImageOptions from './ImageOptions.vue';
export default {
  name: 'ImageView',
  props: {
    accounid: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      documentsArray: [],
    };
  },
  methods: {
    getUrl: src => `/images/thumbnails/${src}`,
    getImages() {
      let obj = { account_id: this.accounid };
      api
        .getImages(obj)
        .then(res => {
          if (Array.isArray(res.data)) this.documentsArray = res.data;
        })
        .catch(err => console.log(err));
    },
  },
  mounted() {
    this.getImages();
  },
};
</script>


<style scoped>
</style>

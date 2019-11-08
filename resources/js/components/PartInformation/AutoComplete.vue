<template>
  <bootstrap-typeahead
    v-model="query"
    :data="items"
    :serializer="item => item.item_purchased"
    :preValue="value"
    @hit="selectItem"
    placeholder="Enter a item"
    textVariant="secondary"
    size="sm"
    backgroundVariant="light"
  />
</template>

<script>
import BootstrapTypeahead from './BootstrapTypeahead.vue';
export default {
  name: 'AutoComplete',

  components: {
    BootstrapTypeahead,
  },

  props: {
    value: String,
  },

  data() {
    return {
      query: '',
      selectedItem: null,
      items: [],
    };
  },

  watch: {
    query(newQuery) {
      axios
        .get(`/api/getrelationdata`, { params: { queryString: newQuery } })
        .then(response => {
          this.items = [];
          response.data.forEach(item => {
            this.items.push(item);
          });
        });
    },
  },

  filters: {
    stringfy(value) {
      return JSON.stringify(value, null, 2);
    },
  },

  methods: {
    selectItem(item) {
      this.$emit('spread', item);
    },
  },
};
</script>

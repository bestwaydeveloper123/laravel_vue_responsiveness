<template>
  <div>
    <table class="table table-bordered bg-gray-200 mb-0">
      <tr>
        <td width="20%">Id</td>
        <!-- <td width="20%">Title</td> -->
        <td width="40%">State</td>
        <td v-if="role == 1" width="40%">Action</td>
      </tr>
      <match-item v-for="item in matchData" :key="item.id" :role="role" :item="item" @update="update"/>
    </table>
  </div>
</template>

<script>
import MatchItem from './MatchItem.vue';

export default {
  components: {
    MatchItem,
  },

  props: {
    data: String,
    topic: String,
    role: String,
  },

  data() {
    return {
      matchData: this.data !== undefined && JSON.parse(this.data),
    };
  },

  created() {},

  computed: {
    result() {},
  },

  methods: {
    update(id, value) {
      window.axios
        .get(`/api/admin/${this.topic}/update`, {
          params: { id: id, publish: value },
        })
        .then(() => {
          this.matchData.find(item => item.id === id).publish = value;
        });
    },
  },
};
</script>

<template>
  <div class="mr-2">
    <div class="card-header pt-1 pb-1 bg-gray-700 theme-color text-light">
      <strong>History</strong>
    </div>
    <div class="card-body pb-0 pt-0 pl-0 pr-0">
    <table class="table border-1">
      <thead>
        <tr>
          <th scope="col" width="33%">JD#</th>
          <th scope="col" width="33%">User</th>
          <th scope="col">Date</th>
        </tr>
      </thead>
      <tbody v-if="searchResult.length > 0">
        <tr v-for="(history, index) in searchResult" :key="index">
          <td width="33%">{{history.inventory_id}}</td>
          <td width="33%">{{history.created_by}}</td>
          <td>{{history.created_at}}</td>
        </tr>
      </tbody>
      <tbody v-else>
        <tr><td colspan="3"><center>- No Data Found -</center></td></tr>
      </tbody>
    </table>
    </div>
  </div>
</template>

<script>
export default {
  name: 'SearchResult',
  props: {
    searchResult: {
      type: Array,
    },
  },
  data() {
    return {
    };
  },
  mounted() {
  },
  methods: {
  },
};
</script>
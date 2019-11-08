
<template>
  <div class="mr-2">
    <div class="card-header pt-1 pb-1 bg-gray-700 theme-color text-light">
      <strong>Patch Notes</strong>
    </div>
    <div class="card-body pb-0 pt-0 pl-0 pr-0">
    <table class="table border-1">
      <thead>
        <tr>
          <th scope="col" width="33%">Id</th>
          <th scope="col" width="33%">Title</th>
          <th scope="col">Content</th>
        </tr>
      </thead>
      <tbody v-if="patchData">
        <tr v-for="(dt, index) in patchData" :key="index">
          <td width="33%">{{ dt.id }}</td>
          <td width="33%">{{ dt.title }}</td>
          <td v-html="dt.content">
            <!-- {{ dt.content }} -->
          </td>
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
  name: 'ViewPatchNotes',
  props: {
    patchData: {
      type: Array
    }
  },
  data() {
    return {  
    };
  },
};
</script>
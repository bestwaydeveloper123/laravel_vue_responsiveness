<template>
  <div class="row">
    <div v-if="user == 'charlespark4393@gmail.com'" class="col-md-6 col-sm-12 card mb-0">
      <div class="card-header pt-1 pb-1 bg-gray-700 theme-color text-light">
        <strong>
          <i class="fa fa-edit"></i> Patch Notes
        </strong>
      </div>
      <div class="card-body pb-0 pt-0 pl-0 pr-0">
        <table class="table mb-0 bg-white">
          <tr>
            <td>
              <input
                v-model="title"
                type="text"
                class="form-control"
                placeholder="Title"
                name="title"
                id="title"
              />
            </td>
          </tr>
          <tr>
            <td>
              <trix-pro-editor  
                @updateContent="updateNotes"
                rows="3"
                placeholder="Content"
                name="content"
                id="content"/>
            </td>
          </tr>
          <tr>
            <td>
              <input @click="save" class="form-control bg-success" type="submit" value="Submit" />
            </td>
          </tr>
        </table>
      </div>
    </div>
    <div :class="userClass" class="card mb-0">
      <ViewPatchNotes :patchData="allData" />
    </div>
  </div>
</template>

<script>
import api from '../../api';
import ViewPatchNotes from './ViewPatchNotes';

export default {
  name: 'PatchNotes',
  components: {
    ViewPatchNotes,
  },

  props: {
    emailAdd: {
      type: String
    }
  },

  data() {
    return {
      title: '',
      content: '',
      allData: [],
      user: this.emailAdd
    };
  },
  mounted() {
    this.getData();
  },
  computed: {
    userClass(){
      if(this.user != 'charlespark4393@gmail.com'){
        return 'col-md-12 col-sm-12'
      } else {
        return 'col-md-6 col-sm-12'
      }
    }
  },
  methods: {
    updateNotes(notes) {
      this.content = notes;
    },
    getData() {
      api
        .GetPatchNotes()
        .then(res => {
          if (res.data.IsSuccess) {
            this.allData = res.data.Data;
          }
        })
        .catch(err => {});
    },
    save() {
      let obj = {
        title: this.title,
        content: this.content,
      };

      api
        .CreatePatchNotes(obj)
        .then(res => {
          if (res.data.IsSuccess) {
            this.allData = res.data.Data;
          }
        })
        .catch(err => {});
    },
  },
};
</script>



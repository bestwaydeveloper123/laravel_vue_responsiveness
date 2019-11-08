<template>
  <div class="main">
    <!-- <div class="row justify-content-end">
      <div class="col-4 input-group mb-2 mt-2 pr-5 align-self-right">
        <input type="text" class="form-control" placeholder="...Search" v-model="terms">
        <div class="input-group-append">
          <button class="btn btn-outline-secondary" type="button" @click="search">Search</button>
        </div>
      </div>
    </div> -->
    <div class="card mb-0">
      <div class="card-header pt-1 pb-1 bg-gray-700 theme-color text-light">
        <strong>
          <i class="fa fa-edit"></i>
          {{ title }}
        </strong>
      </div>
      <div class="card-body table-responsive">
				<div class="jumbotron p-5 bg-primary">
					<div class="col-4 input-group mx-auto">
						<!-- <input type="text" class="form-control form-control-lg" placeholder="Hardware" v-model="terms"> -->
            <input type="text" class="form-control form-control-lg" placeholder="Hardware" id="terms">
						<div class="input-group-append">
							<!-- <button class="btn btn-success" type="button" @click="search"><i class="fa fa-search"></i></button> -->
              <button class="btn btn-success" type="button" id="search-core"><i class="fa fa-search"></i></button>
						</div>
					</div>
				</div>
        <table id="core_table" class="width-100 table table-striped table-hover table-sm inventory-fonts bg-gray-200">
          <thead>
            <tr class="head-column">
              <td scope="col" width="5%">Id</td>
              <td scope="col" width="35%">Title</td>
              <td scope="col" width="15%">Hardware</td>
              <td scope="col" width="15%">Software</td>
              <td scope="col" width="15%">Description</td>
              <td scope="col" width="10%">XYZ Position</td>
              <td scope="col" width="5%">GXX/LSA</td>
            </tr>
          </thead>
          <tbody>
            <!-- <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr> -->
            <!-- <tr class="body-column" v-for="(row, index) in result" :key="index">
              <td>{{ row.title }}</td>
              <td>{{ row.hardware }}</td>
              <td>{{ row.software }}</td>
              <td>{{ row.description }}</td>
              <td>
                {{ row.xyzposition }}
                <i v-if="role == 1"
                  @click="showModal(row.xyzposition, row.id)"
                  class="fa fa-edit float-right btn-sm btn-info cursor-pointer"
                ></i>
              </td>
              <td>{{ row.gxxorlsa }}</td>
            </tr> -->
          </tbody>
        </table>
      </div>
    </div>
    <editor-modal
      :xyzPosition="xyzPosition"
      :xyzId="xyzId"
      v-if="has_modal"
      @save="saveChange"
      @close="close"
    />
  </div>
</template>

<script>
import api from '../../api';
import EditorModal from './EditorModal';
export default {
  components: {
    EditorModal,
  },

  props: {
    coreType: {
      type: String,
      default: 'inventory',
    },
    role: {
      type: String,
      default: '',
    }
  },

  data() {
    return {
      terms: '',
      result: [],
      title: '',
      has_modal: false,
      xyzPosition: '',
      xyzId: '',
    };
  },

  created() {
    this.title = this.coreType;
    let temp = this.title.charAt(0).toUpperCase();
    this.title = temp + this.title.slice(1);
  },

	computed: {
		hasSearched() {
			return typeof this.result !== 'undefined' && this.result.length > 0;
		}
	},

  methods: {
    search() {
			if (this.terms) {
				window.axios
					.get(`/api/${this.coreType}-search`, { params: { terms: this.terms } })
					.then(response => {
						this.result = response.data;
					});
			}
    },
    showModal(val, id) {
      this.xyzPosition = val;
      this.xyzId = id;
      this.has_modal = true;
    },
    saveChange(data) {
      if (this.coreType == 'inventory') {
        const xyzInfo = {
          inventory_id: data.id,
          xyzposition: data.value,
        };
        api
          .updateInventory(xyzInfo)
          .then(res => {
            if (res.data.IsSuccess) {
              this.xyzPosition = res.data.Data.xyzposition;
              this.search();
            }
          })
          .catch(err => console.log(err));
      }
      if (this.coreType == 'core') {
        const xyzInfo = {
          core_id: data.id,
          xyzposition: data.value,
        };
        api
          .updateCore(xyzInfo)
          .then(res => {
            if (res.data.IsSuccess) {
              this.xyzPosition = res.data.Data.xyzposition;
              this.search();
            }
          })
          .catch(err => console.log(err));
      }
      this.has_modal = false;
    },

    close() {
      this.has_modal = false;
    },
  },
};
$(document).ready(function() {
  const urlParams = new URLSearchParams(window.location.search);
  const q = urlParams.get('q');

  var Table = $('#core_table').DataTable({
    order: [[ 0, "desc" ]],
    processing: true,
    serverSide: false,
    responsive: true,
    searching: false,
    language: {
      emptyTable: 'No data available, please search core.',
    },

    columns: [
      { data: 'id' },
      { data: 'title' },
      { data: 'hardware' },
      { data: 'software' },
      { data: 'description' },
      { data: 'xyzposition' },
      { data: 'gxxorlsa' },

      // {data: 'edit', name: 'edit', orderable: false, searchable: false},
    ],
  });
  $('#terms').on('keyup', function(event) {
    if (event.keyCode === 13) {
      $('#search-core').trigger('click');
    }
  })
  $('#search-core').on('click', function(event) {
    let obj = {
      terms: $('#terms').val(),
    };
    $.ajax({
      url: '/api/core-search',
      type: 'GET',
      data: obj,
      dataFilter: function(res) {
        var res_json = JSON.parse(res);
        Table.clear().draw();
        Table.rows.add(res_json).draw();
      },
    });
	});
	$('#search-core').trigger('click');
});
</script>
<style scoped>
.width-100 {
  width: 100% !important;
}
</style>

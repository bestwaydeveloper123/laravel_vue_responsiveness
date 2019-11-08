<template>
  <div class="card mb-0">
    <div class="card-header pt-1 pb-1 bg-gray-700 theme-color text-light">
      <strong>
        <i class="fa fa-edit"></i> Ebay and Website Breakdown
      </strong>
    </div>
    <div class="card-body pb-2">
      <table class="table table-bordered mb-0">
        <tr>
          <td>
            <label class="col-form-label col-form-label-sm">Gold Team</label>
          </td>
          <td class="border-right-none">
            <input
              id="goldteamfrom"
              v-model="goldTeamFrom"
              type="number"
              onkeydown="return event.keyCode !== 69"
              name="goldteamfrom"
              class="form-control form-control-sm"
              placeholder="From"
            />
          </td>
          <td class="border-left-right-none width-1">
            To
          </td>
          <td class="border-left-none">
            <input
              id="goldteamto"
              v-model="goldTeamTo"
              type="number"
              onkeydown="return event.keyCode !== 69"
              name="goldteamto"
              class="form-control form-control-sm"
              placeholder="To"
            />
          </td>
        </tr>
        <tr>
          <td>
            <label class="col-form-label col-form-label-sm">Green Team</label>
          </td>
          <td class="border-right-none">
            <input
              id="greenteamfrom"
              v-model="greenTeamFrom"
              type="number"
              onkeydown="return event.keyCode !== 69"
              name="greenteamfrom"
              class="form-control form-control-sm"
              placeholder="From"
            />
          </td>
          <td class="border-left-right-none width-1">
            To
          </td>
          <td class="border-left-none">
            <input
              id="greenteamto"
              v-model="greenTeamTo"
              type="number"
              onkeydown="return event.keyCode !== 69"
              name="greenteamto"
              class="form-control form-control-sm"
              placeholder="To"
            />
          </td>
        </tr>
        <tr>
          <td>
            <label class="col-form-label col-form-label-sm">Pink Team</label>
          </td>
          <td class="border-right-none">
            <input
              id="pinkteamfrom"
              v-model="pinkTeamFrom"
              type="number"
              onkeydown="return event.keyCode !== 69"
              name="pinkteamfrom"
              class="form-control form-control-sm"
              placeholder="From"
            />
          </td>
          <td class="border-left-right-none width-1">
            To
          </td>
          <td class="border-left-none">
            <input
              id="pinkteamto"
              v-model="pinkTeamTo"
              type="number"
              onkeydown="return event.keyCode !== 69"
              name="pinkteamto"
              class="form-control form-control-sm"
              placeholder="To"
            />
          </td>
        </tr>
        <tr>
          <td>
            <label class="col-form-label col-form-label-sm">Purple Team</label>
          </td>
          <td class="border-right-none">
            <input
              id="purpleteamfrom"
              v-model="purpleTeamFrom"
              type="number"
              onkeydown="return event.keyCode !== 69"
              name="purpleteamfrom"
              class="form-control form-control-sm"
              placeholder="From"
            />
          </td>
          <td class="border-left-right-none width-1">
            To
          </td>
          <td class="border-left-none">
            <input
              id="purpleteamto"
              v-model="purpleTeamTo"
              type="number"
              onkeydown="return event.keyCode !== 69"
              name="purpleteamto"
              class="form-control form-control-sm"
              placeholder="To"
            />
          </td>
        </tr>
        <tr>
          <td>
            <label class="col-form-label col-form-label-sm">Orange Team</label>
          </td>
          <td class="border-right-none">
            <input
              id="orangeteamfrom"
              v-model="orangeTeamFrom"
              type="number"
              onkeydown="return event.keyCode !== 69"
              name="orangeteamfrom"
              class="form-control form-control-sm"
              placeholder="From"
            />
          </td>
          <td class="border-left-right-none width-1">
            To
          </td>
          <td class="border-left-none">
            <input
              id="orangeteamto"
              v-model="orangeTeamTo"
              type="number"
              onkeydown="return event.keyCode !== 69"
              name="orangeteamto"
              class="form-control form-control-sm"
              placeholder="To"
            />
          </td>
        </tr>
      </table>
    </div>
    <div class="card-footer py-0 mt-1 theme-color put-right pr-5">
      <button type="button" @click="saveTeamData" class="btn btn-success">Save</button>
    </div>
  </div>
</template>

<script>
import api from '../../api';

export default {
  name: 'EbayWebsiteBreakDown',

  data() {
    return {
      goldTeamFrom: '',
      goldTeamTo: '',
      greenTeamFrom: '',
      greenTeamTo: '',
      pinkTeamFrom: '',
      pinkTeamTo: '',
      purpleTeamFrom: '',
      purpleTeamTo: '',
      orangeTeamFrom: '',
      orangeTeamTo: ''
    };
  },
  mounted() {
    this.getApiOrder();
  },
  methods: {
    getApiOrder() {
      api
        .GetApiOrderInsert()
        .then(res => {
          if (res.data.success) {
            this.goldTeamFrom = res.data.data[0].valuefrom;
            this.goldTeamTo = res.data.data[0].valueto;
            this.greenTeamFrom = res.data.data[1].valuefrom;
            this.greenTeamTo = res.data.data[1].valueto;
            this.pinkTeamFrom = res.data.data[2].valuefrom;
            this.pinkTeamTo = res.data.data[2].valueto;
            this.purpleTeamFrom = res.data.data[3].valuefrom;
            this.purpleTeamTo = res.data.data[3].valueto;
            this.orangeTeamFrom = res.data.data[4].valuefrom;
            this.orangeTeamTo = res.data.data[4].valueto;
          }
        })
        .catch(err => console.log(err));
    },
    saveTeamData() {
      let obj = {
        ApiOrderinsert: [
          {
            team: 'goldteam',
            valuefrom: this.goldTeamFrom,
            valueto: this.goldTeamTo,
          },
          {
            team: 'greenteam',
            valuefrom: this.greenTeamFrom,
            valueto: this.greenTeamTo,
          },
          {
            team: 'pinkteam',
            valuefrom: this.pinkTeamFrom,
            valueto: this.pinkTeamTo,
          },
          {
            team: 'purpleteam',
            valuefrom: this.purpleTeamFrom,
            valueto: this.purpleTeamTo,
          },
          {
            team: 'orangeteam',
            valuefrom: this.orangeTeamFrom,
            valueto: this.orangeTeamTo,
          }
        ],
      };
      api
        .UpdateApiOrderInsert(obj)
        .then(res => {
          if (res.data.success) {
            this.goldTeamFrom = res.data.data[0].valuefrom;
            this.goldTeamTo = res.data.data[0].valueto;
            this.greenTeamFrom = res.data.data[1].valuefrom;
            this.greenTeamTo = res.data.data[1].valueto;
            this.pinkTeamFrom = res.data.data[2].valuefrom;
            this.pinkTeamTo = res.data.data[2].valueto;
            this.purpleTeamFrom = res.data.data[3].valuefrom;
            this.purpleTeamTo = res.data.data[3].valueto;
            this.orangeTeamFrom = res.data.data[4].valuefrom;
            this.orangeTeamTo = res.data.data[4].valueto;
          }
        })
        .catch(err => console.log(err));
    },
  },
};
</script>


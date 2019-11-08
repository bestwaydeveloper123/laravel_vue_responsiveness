<template>
  <div class="card mb-1" v-if="orderType == 'Stock'">
    <div class="card-header py-1 bg-gray-700 theme-color text-light">
      <strong>
        <i class="fa fa-info-circle" aria-hidden="true"></i> Stock Information
      </strong>
    </div>
    <div class="card-body p-0">
      <table id="stock_information" class="table mb-0">
        <tr>
          <td class="pt-0 pb-0">
            <label for="text-input" class="col-form-label col-form-label-sm">Stock Part Type</label>
          </td>
          <td class="pt-0 pb-0">
            <select
              name="stock_master_id"
              id="stock_master_id"
              class="form-control-sm form-control"
            >
              <option
                v-if="selectedPart"
                selected
                :value="selectedPartType.stock_master_id"
              >{{ selectedPart }}</option>
              <option v-else disabled selected value>Select</option>
              <option
                v-for="(type) in stockPartType"
                :key="type.id"
                :value="type.id"
              >{{ type.part_ype }}</option>
            </select>
          </td>
        </tr>
        <tr>
          <td class="pt-0 pb-0">
            <label for="stock_lbo" class="col-form-label col-form-label-sm">Stock LBO</label>
          </td>
          <td class="pt-0 pb-0">
            <div class="custom-control custom-checkbox">
              <input
                v-model="selectedStocklbo"
                type="checkbox"
                name="stock_lbo"
                id="stock_lbo"
                class="custom-control-input"
              />
              <label for="stock_lbo" class="custom-control-label"></label>
            </div>
          </td>
        </tr>
        <tr>
          <td class="pt-0 pb-0" colspan="2">
              <label
                class="col-form-label col-form-label-sm"
              >*Please Use Price Information for Part Prices.</label>
          </td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
import api from '../../api';

export default {
  name: 'StockInformation',
  props: {
    //orderType: String,
    stockInfo: String,
  },
  data() {
    return {
      stockPartType: [],
      selectedPartType: {},
      selectedPart: '',
      selectedStocklbo: false,
      orderType: '',
      timer: '',
    };
  },
  mounted() {
    if (this.stockInfo) {
      this.selectedPartType = JSON.parse(this.stockInfo);
      this.selectedPart = this.selectedPartType.stock_master
        ? this.selectedPartType.stock_master.part_ype
        : '';
      this.selectedStocklbo = this.selectedPartType
        ? this.selectedPartType.stock_lbo
        : '';
    }
    this.getStockMaster();
    this.getOrderType();
  },
  created: function() {
    this.timer = setInterval(this.getOrderType, 100);
  },
  methods: {
    getOrderType() {
      this.orderType = document.getElementById('order-type-ctrl2').value;
    },
    getStockMaster() {
      api
        .StockMaster()
        .then(res => {
          if (res.data) {
            this.stockPartType = res.data.data;
          }
        })
        .catch(err => console.log(err));
    },
  },
};
</script>

<style scoped>
.icon-attribute {
  padding: 5px;
  margin: 4px;
  border-radius: 5px;
  width: 42px;
  height: 42px;
  text-align: center;
}
.icon-attribute-level {
  padding-top: 4px;
  margin: 4px;
  border-radius: 5px;
  text-align: center;
  width: 42px;
  height: 42px;
  cursor: default;
}
</style>


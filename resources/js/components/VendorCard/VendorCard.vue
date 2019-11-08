<template>
  <div class="col-sm-4 col-md-4 word-break">
    <div class="card mb-0 mt-1">
      <div class="card-header py-1" :class="headerStyle">
        Vendor {{ index + 1 }}
        <span class="badge badge-pill badge-danger float-right cursor-pointer mt-1" @click="edit">edit</span>
        <span class="btn btn-sm badge-success float-right mr-2 ml-5 pt-0 pb-0" @click="generateLabel">
          Generate Label
          <i class="fa fa-plus"></i>
        </span>
      </div>
      <div class="card-body pb-0 pt-0 pl-0 pr-0">
        <table
          class="table table-bordered table-striped table-responsive-sm table-sm pull-left mb-0"
        >
          <tr>
            <td class="pt-0 pb-0">
              <label class="col-form-label col-form-label-sm">
                <i class="fa fa-address-card"></i>
              </label>
            </td>
            <td class="pt-0 pb-0" colspan="3">
              <strong>{{ data.purchasedfrom }}</strong>
            </td>
            <td class="pt-0 pb-0" colspan="3">
              <strong><textarea class="form-control form-control-sm bg-gray-100 border-none resize-none" rows="1" v-html="data.salespersonstockno" readonly></textarea></strong>
            </td>
          </tr>
          <tr>
            <td class="pt-0 pb-0">
              <label class="col-form-label col-form-label-sm">
                <i class="fa fa-truck"></i>
              </label>
            </td>
            <td class="bg-light theme-color" colspan="2">
              <strong>Part Price:</strong>
              {{ data.partprice ? data.partprice : '' }}
            </td>
            <td class="bg-light theme-color" colspan="2">
              <strong>Shipping Price:</strong>
              {{ data.shippingprice ? data.shippingprice : '' }}
            </td>
            <td class="bg-light theme-color" colspan="2">
              <strong>Pickup Charge:</strong>
              {{ data.pickupcharge ? data.pickupcharge : '' }}
            </td>
          </tr>
          <tr>
            <td class="pt-0 pb-0">
              <label class="col-form-label col-form-label-sm">
                <i class="fa fa-calendar-times-o"></i>
              </label>
            </td>
            <td class="pt-0 pb-0" colspan="3">
              <strong>Purchased:</strong>
              {{ data.datepurchased ? data.datepurchased : '' }}
            </td>
            <td class="pt-0 pb-0" colspan="3">
              <strong>Delivery:</strong>
              {{ data.deliverydate ? data.deliverydate : '' }}
            </td>
          </tr>
          <tr>
            <td class="pt-0 pb-0">
              <label class="col-form-label col-form-label-sm">
                <i class="fa fa-barcode"></i>
              </label>
            </td>
            <td class="bg-light theme-color form-control-sm" colspan="6">{{ data.trackingnumber }}</td>
          </tr>
          <tr>
            <td class="pt-0 pb-0">
              <label class="col-form-label col-form-label-sm">
                <i class="fa fa-credit-card"></i>
              </label>
            </td>
            <td class="bg-light theme-color form-control-sm" colspan="6">{{ data.creditcard }}</td>
            <td v-show="false">{{ data.confirmation }}</td>
            <td v-show="false">{{ data.refunded }}</td>
            <td v-show="false">{{ data.spoke_to }}</td>
          </tr>
          <tr
            v-if="data.shippinglabel"
          >
            <td class="pt-0 pb-0" colspan="4">
              <label class="col-form-label col-form-label-sm">
                <i class="fa fa-reply"></i>
                <strong>Shipping Label</strong>
              </label>
            </td>
            <td class="pt-0 pb-0 align-middle" colspan="2">
              <a :href="data.shippinglabel" target="_blank">
                <span class="btn btn-sm badge-success float-right mr-5 pt-0 pb-0">Shipping Label</span>
              </a>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'VendorCard',
  props: {
    data: {
      type: Object,
      required: true,
    },
    index: {
      type: Number,
      default: 0,
    },
    creditCard: {
      type: String,
      default: ""
    },
    headerStyle: String,
  },
  methods: {
    edit() {
      this.$emit('edit', this.data, this.index);
    },
    generateLabel() {
      this.$emit('generate-label',this.data, this.index);
    },
  },
};
</script>

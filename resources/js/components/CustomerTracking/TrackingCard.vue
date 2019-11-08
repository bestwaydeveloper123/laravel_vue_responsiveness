<template>
  <div class="col-sm-4">
    <div class="card mb-0 mt-1">
      <div class="card-header py-1" :class="headerStyle">
        Tracking {{ index + 1 }}
        <span
          class="btn btn-sm badge-danger float-right py-0"
          @click="edit"
        >Edit</span>
        <span
          v-if="!data.trackingcode"
          class="btn btn-sm badge-success float-right mr-1 py-0"
          @click="generateLabel"
        >
          Generate Label <i class="fa fa-plus"></i>
        </span>
      </div>
      <div class="card-body pb-0 pt-0 pl-0 pr-0" style="overflow: auto">
        <table
          class="table table-bordered table-striped table-responsive-sm table-sm pull-left mb-0"
        >
          <tr>
            <td class="py-0" width="40%">
              <label class="col-form-label col-form-label-sm">
                <i class="fa fa-reply-all"></i>
                <strong>Direction</strong>
              </label>
            </td>
            <td class="py-0">
              <strong>{{ data.direction }}</strong>
            </td>
          </tr>
          <tr v-if="isOutBound">
            <td class="py-0" width="40%">
              <label class="col-form-label col-form-label-sm">
                <i class="fa fa-calendar"></i>
                <strong>Date Shipped</strong>
              </label>
            </td>
            <td class="py-0">
              <strong>{{ data.dateshippedtocustomer }}</strong>
            </td>
          </tr>
          <tr v-if="isOutBound">
            <td class="py-0" width="40%">
              <label class="col-form-label col-form-label-sm">
                <i class="fa fa-calendar"></i>
                <strong>Delivery Date</strong>
              </label>
            </td>
            <td class="py-0">
              <strong>{{ data.deliverydatetocustomer }}</strong>
            </td>
          </tr>
          <tr v-if="isOutBound">
            <td class="py-0">
              <label class="col-form-label col-form-label-sm">
                <i class="fa fa-truck"></i>
                <strong>Tracking #:</strong>
              </label>
            </td>
            <td class="py-0 font-weight-700" colspan="2" style="word-break: break-word;">
              <p class="p-tag" v-if="data.customertrackingnumber">
                {{ data.customertrackingnumber }}
              </p>
              <p class="p-tag" v-if="data.deliverydatetocustomer">
                {{ data.deliverydatetocustomer }}
              </p>
              <p class="pt-2 pb-3" v-if="data.trackingcode && data.realtimetracking">
                <a :href="data.realtimetracking" target="_blank">
                  <span class="btn btn-sm badge-success float-right py-0">RealTime Tracking</span>
                </a>
              </p>
            </td>
          </tr>
          <tr>
            <td class="py-0">
              <label class="col-form-label col-form-label-sm">
                <i class="fa fa-undo"></i>
                <strong>Return Tracking</strong>
              </label>
            </td>
            <td class="py-0 font-weight-700" colspan="2" style="word-break: break-word;">
              <!-- {{ data.returntrackinginfo }} -->
              <p class="p-tag" v-if="data.returntrackinginfo">
                {{ data.returntrackinginfo }}
              </p>
              <p class="pt-2 pb-3" v-if="data.returntrackinginfo">
                <a :href="data.returntrackinginfo" target="_blank">
                  <span class="btn btn-sm badge-success float-right py-0">RealTime Tracking</span>
                </a>
              </p>  
            </td>
          </tr>
          <tr v-if="isOutBound">
            <td class="py-0" width="40%">
              <label class="col-form-label col-form-label-sm">
                &nbsp;
                <i class="fa fa-exclamation"></i>
                <strong>Anti theft</strong>
              </label>
            </td>
            <td class="py-0">
              <strong>{{ data.antitheft }}</strong>
            </td>
          </tr>
          <tr>
            <td class="py-0">
              <label class="col-form-label col-form-label-sm">
                <i class="fa fa-quote-left"></i>
                <strong>Warranty #:</strong>
              </label>
            </td>
            <td class="py-0 font-weight-700" colspan="2">{{ data.warrantysticker }}</td>
          </tr>
          <tr v-if="isOutBound">
            <td class="py-0">
              <label class="col-form-label col-form-label-sm">
                <i class="fa fa-calendar-times-o"></i>
                <strong>Warranty Exhausted:</strong>
              </label>
            </td>
            <td class="py-0" colspan="2">
              <p v-if="data.warrantyexhausted" class="text-center mb-0">
                <i class="fa fa-check text-success"></i>
              </p>
            </td>
          </tr>
          <tr v-if="isInBound">
            <td class="py-0" width="40%">
              <label class="col-form-label col-form-label-sm">
                <i class="fa fa-calendar"></i>
                <strong>Return Shipping Date</strong>
              </label>
            </td>
            <td class="py-0">
              <strong>{{ data.returnShippingDate }}</strong>
            </td>
          </tr>
          <tr v-if="isInBound">
            <td class="py-0">
              <label class="col-form-label col-form-label-sm">
                <i class="fa fa-exclamation"></i>
                <strong>Unauthorized:</strong>
              </label>
            </td>
            <td class="py-0 align-middle" colspan="2">
              <p v-if="data.unauthorized" class="text-center mb-0">
                <i class="fa fa-check text-success"></i>
              </p>
            </td>
          </tr>
          <tr v-if="isInBound">
            <td class="py-0">
              <label class="col-form-label col-form-label-sm">
                <i class="fa fa-user"></i>
                <strong>Customer Provide Tracking:</strong>
              </label>
            </td>
            <td class="py-0 align-middle font-weight-700" colspan="2">
              <div v-if="data.customerProvideTracking" class="text-center mb-0">
                <i class="fa fa-check text-success"></i>
                <br />
                <p class="border">
                  <span>Return Shipping Cost</span>
                  <strong>{{ data.returnShippingCost }}</strong>
                </p>
              </div>
            </td>
          </tr>
          <tr>
            <td class="py-0">
              <label class="col-form-label col-form-label-sm">
                <i class="fa fa-reply"></i>
                <strong>RMA:</strong>
              </label>
            </td>
            <td class="py-0 align-middle" colspan="2">
              <p v-if="data.rma" class="text-center mb-0">
                <i class="fa fa-check text-success"></i>
              </p>
            </td>
          </tr>
          <tr v-if="data.shippinglabel">
            <td class="py-0">
              <label class="col-form-label col-form-label-sm">
                <i class="fa fa-reply"></i>
                <strong>Shipping Label</strong>
              </label>
            </td>
            <td class="py-0 align-middle" colspan="2">
              <a :href="data.shippinglabel" target="_blank">
                <span class="btn btn-sm badge-success float-right mr-5 py-0">Shipping Label</span>
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
  name: 'TrackingCard',
  props: {
    data: {
      type: Object,
      default: null,
    },
    index: {
      type: Number,
      default: 0,
    },
    headerStyle: String,
  },
  computed: {
    isOutBound() {
      return this.data && this.data.direction == 'OutBound';
    },
    isInBound() {
      return this.data && this.data.direction == 'InBound';
    }
  },
  methods: {
    edit() {
      this.$emit('edit', this.data, this.index);
    },
    generateLabel() {
      this.$emit('generate-label', this.data, this.index);
    },
  },
};
</script>
<style scoped>
.p-tag {
  margin-bottom: 0px;
    border: 1px solid #ddd;
    padding: 5px;
}
</style>

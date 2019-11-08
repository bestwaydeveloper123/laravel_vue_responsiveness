<template>
  <div class="card mb-1">
    <div class="card-header py-1 bg-gray-700 theme-color text-light">
      <strong>
        <i class="fa fa-money"></i> Price Information
      </strong>
    </div>
    <div class="card-body p-0">
      <table
        id="price-information"
        class="table table-sm table-bordered table-responsive-sm pull-left mb-0"
      >
        <tr>
          <td class="py-0" width="15%">
            <label class="col-form-label col-form-label-sm" for="sku-price">SKU Price</label>
          </td>
          <td class="py-0" colspan="3">
            <input
              class="form-control form-control-sm"
              type="text"
              id="sku-price"
              v-model="skuPrice"
              name="skuprice"
            />
          </td>
        </tr>
        <tr>
          <td class="py-0">
            <label class="col-form-label col-form-label-sm" for="discount">Discount</label>
          </td>
          <td class="py-0" colspan="3">
            <input
              class="form-control form-control-sm bg-gray-100"
              type="text"
              id="discount"
              :value="discount + discountPercent"
              readonly
            />
          </td>
        </tr>
        <tr>
          <td class="py-0">
            <label class="col-form-label col-form-label-sm" for="adjustment">Adjustment</label>
          </td>
          <td class="py-0" colspan="1" width="35%">
            <input
              class="form-control form-control-sm"
              type="text"
              id="adjustment"
              v-model="adjustment"
              name="adjustment"
            />
          </td>
          <td class="py-0" colspan="1" width="15%">
            <label class="col-form-label col-form-label-sm" for="reason">Reason</label>
          </td>
          <td class="py-0" colspan="1">
            <textarea
              class="form-control form-control-sm"
              id="reason"
              rows="1"
              :value="reason"
              name="reason"
            />
          </td>
        </tr>
        <tr>
          <td class="py-0">
            <label class="col-form-label col-form-label-sm" for="taxes">Taxes</label>
          </td>
          <td class="py-0" colspan="1">
            <input
              class="form-control form-control-sm bg-gray-100"
              type="text"
              id="taxes"
              :value="taxes + taxesPercent"
              readonly
            />
          </td>
          <td class="py-0" colspan="2">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" id="exempt" v-model="exempt" />
              <label class="custom-control-label" for="exempt">Exempt</label>
            </div>
          </td>
        </tr>
        <tr v-if="exempt">
          <td class="py-0">
            <label class="col-form-label col-form-label-sm" for="resale-number">Resale #</label>
          </td>
          <td class="py-0" colspan="3">
            <input
              class="form-control form-control-sm"
              type="text"
              id="resale-number"
              :value="resaleNumber"
              name="resale_number"
            />
          </td>
        </tr>
        <tr>
          <td class="py-0">
            <label class="col-form-label col-form-label-sm" for="final-price">Final Price</label>
          </td>
          <td class="py-0" colspan="3">
            <input
              class="form-control form-control-sm bg-gray-100"
              type="text"
              id="final-price"
              :value="finalPrice"
              name="pricepaid"
              readonly
            />
            <div
              v-if="!customerValid"
              class="alert alert-sm alert-danger alert-dismissible fade show py-1 mb-0"
              role="alert"
            >
              Please check
              <strong>Customer</strong> information.
              <button
                type="button"
                class="close p-1 mr-2"
                data-dismiss="alert"
                aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          </td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
import * as filter from '../../services/filter.js';

export default {
  name: 'PriceInformation',
  props: {
    customer: {
      type: Object,
      default: null,
    },
    account: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      skuPrice: 0,
      adjustment: 0,
      reason: '',
      resaleNumber: '',
      pricePaid: 0,
      exempt: false,
      orderType: '',
      customerValid: true,
    };
  },
  created() {
    if (Object.keys(this.account).length > 0) {
      this.skuPrice = Number(this.account.skuprice);
      this.adjustment = Number(this.account.adjustment);
      this.reason = this.account.reason;
      this.pricePaid = this.account.pricepaid;
      this.resaleNumber = this.account.resale_number;
      this.orderType = this.account.ordertype;
    }
    if (this.resaleNumber) {
      this.exempt = true;
    }
  },
  mounted() {
    filter.setInputFilter(
      document.getElementById('sku-price'),
      filter.deciamlNumFlt,
    );
    filter.setInputFilter(
      document.getElementById('adjustment'),
      filter.deciamlNumFlt,
    );
  },
  updated() {
    if (this.exempt) {
      filter.setInputFilter(
        document.getElementById('resale-number'),
        filter.letterNumFlt,
      );
    }
    this.customerValid = this.isValidCustomer;
  },
  computed: {
    discount() {
      if (this.customer && this.skuPrice) {
        if (
          this.customer.level == null ||
          this.customer.level.indexOf('Normal') > -1
        ) {
          return 0;
        } else if (this.customer.level.indexOf('Plus') > -1) {
          return Number((this.skuPrice * 0.05).toFixed(2));
        } else if (this.customer.level.indexOf('Premier') > -1) {
          return Number((this.skuPrice * 0.1).toFixed(2));
        } else if (this.customer.level.indexOf('Platinum') > -1) {
          return Number((this.skuPrice * 0.15).toFixed(2));
        }
      }
      return '';
    },
    discountPercent() {
      if (this.discount && this.customer) {
        if (
          this.customer.level == null ||
          this.customer.level.indexOf('Normal') > -1
        ) {
          return '';
        } else if (this.customer.level.indexOf('Plus') > -1) {
          return ' (5%)';
        } else if (this.customer.level.indexOf('Premier') > -1) {
          return ' (10%)';
        } else if (this.customer.level.indexOf('Platinum') > -1) {
          return ' (15%)';
        }
      }
      return '';
    },
    taxes() {
      if (this.isValidCustomer && this.skuPrice) {
        if (this.customer.state === 'NY' && !this.isFixedOrderType)
          if (this.orderType != 'Stock')
            return Number((this.skuPrice * 0.0875).toFixed(2));
        return 0;
      }
      return '';
    },
    taxesPercent() {
      if (typeof this.taxes == 'number') {
        if (this.customer.state === 'NY') {
          if (this.orderType != 'Stock') {
            if (this.isFixedOrderType) {
              return ' (Sales Tax Included in Price)';
            }
            return ' (8.875%)';
          }
        }
        return ' (0%)';
      }
      return '';
    },
    finalPrice() {
      if (this.isValidCustomer) {
        let finalPrice = Number(this.skuPrice) + Number(this.adjustment);
        if (typeof this.taxes == 'number' && !this.exempt) {
          finalPrice += this.taxes;
        }
        if (typeof this.discount == 'number') {
          finalPrice -= this.discount;
        }
        return finalPrice.toFixed(2);
      }
      return this.pricePaid;
    },
    isValidCustomer() {
      if (this.customer && this.customer.state) return true;
      return false;
    },
    isFixedOrderType() {
      if (this.orderType == 'eBay- fs1inc' || this.orderType == 'Website') {
        return true;
      }
      return false;
    },
  },
};
</script>

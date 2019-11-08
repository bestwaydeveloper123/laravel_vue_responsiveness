<template>
  <div>
    <div style="display: block;position: fixed;right: 10px;padding: 8px;">
      <!-- <button v-if="img" disabled @click="sendToCustomer" class="btn btn-success" type="button">
        <i class="fa fa-paper-plane"></i> Email to <br/>Customer
      </button> -->
      <button @click="sendToCustomer" class="btn btn-success" type="button">
        <i class="fa fa-paper-plane"></i> Email to <br/>Customer
      </button>
    </div>
    <div style="display: block;position: fixed;right: 10px;padding: 8px;top:170px">
      <button @click="backToEdit()" class="btn btn-primary" type="button">
        <i class="fa fa-chevron-left"></i> Back
      </button>
    </div>
    <div class="doc-container">
      <div class="border-1" style="min-height:1260px;">
        <div style="padding:40px 20px">
          <p>
            Hello charles park,
            <br />Thank you for ordering from Flagship One Inc!
          </p>
          <p>
            If you have any questions about your order please contact us at
            <a
              href="mailto:admin@fs1inc.com"
            >admin@fs1inc.com</a> or call us at (516) 766-2223. Our customer service representatives are available 7 days a week to answer any questions or concerns you may have with your order.
          </p>
          <p class="put-center" style="font-size:18px">
            <strong>Your Invoice for Order #H{{ docData.id }}</strong>
          </p>
        </div>
        <div class="row" style="padding:0px 10px">
          <div class="col-md-6 col-sm-12">
            <div class="info-method">
              <strong>Billing Information:</strong>
            </div>
            <div class="info-method2">
              <p>
                charles park
                <br />32-26 168th Street
                <br />Flushing, New York, 11358
                <br />United States
                <br />T: 9175971119
              </p>
            </div>
          </div>
          <div class="col-md-6 col-sm-12">
            <div class="info-method">
              <strong>Shipping Information:</strong>
            </div>
            <div class="info-method2">
              <p>
                charles park
                <br />32-26 168th Street
                <br />Flushing, New York, 11358
                <br />United States
                <br />T: 9175971119
              </p>
            </div>
          </div>
          <!-- <div class="col-md-6 col-sm-12">
            <div class="info-method">
              <strong>Payment Method:</strong>
            </div>
            <div class="info-method2">
              <p>
                <b>Credit Card (Authorize.net)</b>
                <br />
                <b>Credit Card Type:</b> Visa
                <br />
                <b>Credit Card Number:</b> xxxx-5181
                <br />
                <b>Processed Amount:</b> $0.01
              </p>
            </div>
          </div> -->
        </div>
        <div class="row" style="padding:0px 10px;margin-top:30px">
          <!-- <div class="col-md-6 col-sm-12">
            <div class="info-method">
              <strong>Shipping Information:</strong>
            </div>
            <div class="info-method2">
              <p>
                charles park
                <br />32-26 168th Street
                <br />Flushing, New York, 11358
                <br />United States
                <br />T: 9175971119
              </p>
            </div>
          </div> -->
          <!-- <div class="col-md-6 col-sm-12">
            <div class="info-method">
              <strong>Shipping Method:</strong>
            </div>
            <div class="info-method2">
              <p>Free Shipping - (1-6 Business Days)</p>
            </div>
          </div> -->
        </div>
        <div class="container">
          <table width="100%" class="put-center" style="border:1px solid #99ccff;padding:5px">
            <thead>
              <tr>
                <td>
                  <b>Item in your order</b>
                </td>
                <td>
                  <b>Qty</b>
                </td>
                <td>
                  <b>Price</b>
                </td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>test product</td>
                <td>1</td>
                <td>${{docData.pricepaid}}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="container" style="text-align:right;font-size: 22px;">
          <p style="border-bottom:1px solid #99ccff;width: 35%;float: right;">
            Subtotal &nbsp;&nbsp; ${{docData.pricepaid}}
            <br />
            Shipping & Handling &nbsp;&nbsp; ${{docData.pricefspaidforshipping}}
            <br />
            <b>Grand Total &nbsp;&nbsp; ${{Number(parseFloat(docData.pricepaid) + parseFloat(docData.pricefspaidforshipping)).toFixed(2)}}</b>
          </p>
        </div>
        <div style="height:200px">
          <div v-if="img" class="signature-1">
            <img :src="imgUrl(img)" width="280px" height="150px" />
          </div>
          <div v-else class="signature-2">Sign Here</div>
        </div>
        <div style="padding:0px 10px;">
          <div class="note">
            <p>All items come with a lifetime warranty. Warranty does not include any labor associated with installation and/or removal of parts, key and/or Locksmith fees. Buyer will not be reimbursed for any such fees--NO EXCEPTIONS. Buyer hereby acknowledges and agrees that the Seller's liability is limited to the price of the item sold and Seller is not liable for any damage and/or injury sustained that results from any item(s) sold by any entity operated by Flagship One, Inc and Buyer hereby now and forever relinquishes Seller from any such liability. Returns must be in original condition and be accompanied by a Return Merchandise Authorization (RMA) otherwise the return will be deemed unauthorized. Unauthorized returns will not be processed and will be held for no more than 45 days after receipt. After 45 days, if an unauthorized return is not claimed or accompanied by an RMA, Seller reserves the right to maintain possession of the unclaimed/unauthorized return. Electrical parts are tested prior to purchase and if returned, all units will be inspected for burnt components, physical damage and water damage. Returns will be processed in the order received and may have a greater handling time than order processing. The lifetime warranty shall be void if an item is returned with any signs of: (a)burnt components; (b)physical and/or water damage; (c)misuse, abuse, modifications, opened, tampered with, and/or used for any purpose not originally intended; (d)vehicle is involved in a collision; or (e)security seal is removed, broken and/or damaged. Buyer must activate warranty within 20 days of receipt to be valid. Returns are subject to a 20% restocking fee. Returned programmed items are subject to an additional $85 non-refundable programming fee and, if Buyer purchased keys, the return is subject to an additional $90 non-refundable key fee.</p>

            <p>
              All items have a 1-3 full business day (Mon. - Fri. excluding holidays) handling time after the order is fully processed for refurbishing/programming unless otherwise expressly stated.
              Upon completion of refurbishing/programming, your item will be shipped using USPS Priority Mail unless an upgraded method of shipment is purchased.
              All units which finalize refurbishing/programming BEFORE 3:00 p.m. EST, will be shipped that same day.
              All units which finalize refurbishing/programming AFTER 3:00 p.m. EST, will be shipped the following business day.
            </p>

            <p>Shipping times within the country vary but are generally between 1-3 shipping days (Mon. - Sat. excluding holidays). Customers will be notified of any delay via email.</p>
          </div>
        </div>
      </div>
    </div>
    <loading :active.sync="isLoading" :is-full-page="fullPage"></loading>
  </div>
</template>

<script>
import api from '../../api';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
  name: 'Docusign',

  components: {
    Loading,
  },

  data() {
    return {
      isLoading: false,
      fullPage: true,
      img: '',
      docData: {
        id: '',
        pricefspaidforshipping: '',
        pricepaid:''
      },
    };
  },

  mounted() {
    this.getDocusignData();
  },

  methods: {
    sendToCustomer() {
      let obj = {
        account_id: localStorage.getItem('accountId'),
      };
      this.isLoading = true;
      api
        .SendDocusignFormEmail(obj)
        .then(res => {
          if (res) {
            setTimeout(() => {
              this.isLoading = false;
            }, 500);
            if (res.data.IsSuccess) {
              this.$toast.open({
                message: res.data.Message,
                position: 'top',
              });
            } else {
              this.$toast.open({
                message: res.data.Message,
                position: 'top',
                type: 'error',
              });
            }
          }
        })
        .catch(
          err => (
            (this.isLoading = false),
            this.$toast.open({
              message: 'Something went wrong!',
              position: 'top',
              type: 'error',
            })
          ),
        );
    },
    getDocusignData() {
      let obj = {
        account_id: localStorage.getItem('accountId'),
      };
      api
        .GetDocusignData(obj)
        .then(res => {
          if (res.data.IsSuccess) {
            this.img = res.data.Data.DocusignForm?res.data.Data.DocusignForm.customer_signature:'';
            this.signImgForUpdate = res.data.Data.DocusignForm?res.data.Data.DocusignForm.customer_signature:'';
            this.docData.id = res.data.Data.id;
            this.docData.pricefspaidforshipping = res.data.Data.pricefspaidforshipping;
            this.docData.pricepaid = res.data.Data.pricepaid;
          }
        })
        .catch(err => {});
    },
    backToEdit() {
      window.location.href = '/accounts/' + localStorage.getItem('accountId') + '/edit';
    },
    imgUrl(img) {
      var base64regex = /^([0-9a-zA-Z+/]{4})*(([0-9a-zA-Z+/]{2}==)|([0-9a-zA-Z+/]{3}=))?$/;
      if (base64regex.test(img.split(',')[1])) {
        return img;
      } else {
        return `/images/CustomerSignature/${img}`;
      }
    },
  },
};
</script>

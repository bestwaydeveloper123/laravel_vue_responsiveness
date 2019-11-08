<template>
  <div>
    <div v-if="isSuccess">
      <div class="rma-submit-btn">
        <button @click="saveRMAForm()" class="btn btn-success" type="button">
          <i class="fa fa-check-square"></i> Submit
        </button>
      </div>
      <div class="main-container">
        <div class="sub-container">
          <div class="container-1">
            <div class="container-2">
              <div>
                <table>
                  <tr>
                    <td
                      class="disclaimer"
                    >ALL FIELDS OF RETURN AUTHORIZATION- FORM MUST BE FILLED OUT, AND THE FORM MUST BE SIGNED AND DATED BEFORE ANY RMA WILL BE WORKED ON/REFUNDED. IN ORDER TO EXPEDITE THE RETURN PROCESS, PLEASE BE SURE TO INCLUDE AS MUCH INFORMATION AS POSSIBLE.</td>
                    <td class="order-date">
                      Date: Aug 02,2019
                      <br />
                      Order# {{ RMAdata.account.account_id }}
                    </td>
                  </tr>
                </table>
                <table class="width-100">
                  <tr>
                    <td class="return-address">
                      Return address:
                      <br />
                      {{ RMAdata.account.account_id }}
                      <br />Flagship One Inc.
                      <br />19 Wilbur Street
                      <br />Lynbrook NY 11563
                      <br />Phone: 516-766-2223
                    </td>
                    <td class="blank-td"></td>
                    <td class="to-address-1">To:</td>
                    <td class="to-address-2">
                      {{ RMAdata.customermetadata.shipto }}
                      <br />
                      {{ RMAdata.customermetadata.shopname }}
                      <br />
                      {{ RMAdata.customermetadata.street1 }}
                      <br />
                      {{ RMAdata.customermetadata.street2 }}
                      <br />
                      {{ RMAdata.customermetadata.city }}
                      {{ RMAdata.customermetadata.state }}
                      {{ RMAdata.customermetadata.zip }}
                    </td>
                  </tr>
                </table>
                <table class="table table-border rma-input">
                  <thead class="bg-1">
                    <tr>
                      <th>ACCOUNT MANAGER</th>
                      <th class="b-right-none">VIN FOR VEHICLE AND PART #</th>
                      <th class="border-none"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td
                        style="width:20%"
                      >{{ RMAdata.account.primaryaccountmanager? RMAdata.account.primaryaccountmanager : RMAdata.account.secondaryaccountmanager }}</td>
                      <td style="width:50%">VIN: {{ RMAdata.account.customervin }}</td>
                      <td>Part# {{ RMAdata.account.wrongpart }}</td>
                    </tr>
                  </tbody>
                </table>
                <table class="table table-bordered rma-input">
                  <thead class="bg-1">
                    <tr>
                      <th>Original problem(s) experienced with vehicle (including all symptoms and trouble-codes)</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="height-100">
                        <textarea
                          id="originalProblem"
                          name="originalProblem"
                          v-model="RMAdata.rmaformdata.originalProblem"
                          rows="4"
                          class="form-control form-control-sm"
                        ></textarea>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <table class="table table-bordered rma-input">
                  <thead class="bg-2">
                    <tr>
                      <th>Steps taken to diagnose the original problem(s)</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="height-100">
                        <textarea
                          id="steps-Taken"
                          name="stepsTaken"
                          v-model="RMAdata.rmaformdata.stepsTaken"
                          rows="4"
                          class="form-control form-control-sm"
                        ></textarea>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <table class="table table-bordered rma-input">
                  <thead class="bg-2">
                    <tr>
                      <th>Problem(s) experienced with the part received from Flagship One</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="height-100">
                        <textarea
                          id="problempExperienced"
                          name="problemExperienced"
                          v-model="RMAdata.rmaformdata.problemExperienced"
                          placeholder
                          rows="4"
                          class="form-control form-control-sm"
                        ></textarea>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <table class="table table-bordered rma-input">
                  <thead class="bg-2">
                    <tr>
                      <th>Steps taken to diagnose the problem(s) with the part from Flagship One</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="height-100">
                        <textarea
                          id="stepsTakenForPart"
                          name="stepsTakenForPart"
                          v-model="RMAdata.rmaformdata.stepsTakenForPart"
                          rows="4"
                          class="form-control form-control-sm"
                        ></textarea>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <table class="table table-bordered rma-input">
                  <thead class="bg-2">
                    <tr>
                      <th>Additional notes</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="height-100">
                        <textarea
                          id="additionalNotes"
                          name="additionalNotes"
                          v-model="RMAdata.rmaformdata.additionalNotes"
                          rows="4"
                          class="form-control form-control-sm"
                        ></textarea>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="policy-content">
                <p>
                  <b>Return authorized by: XX</b>
                </p>
                <span>All of our items come with a lifetime warranty. Warranty does not include any labor associated with installation and/or removal of parts, key and/or Locksmith fees, FlagshipOne, Inc. will not reimburse Buyer for any such fees. Warranty will include the shipping charge only when the part is deemed to be faulty without being damaged by Buyer which is under the sole discretion of FlagshipOne ,Inc. Buyer hereby acknowledges that the Seller’s liability is limited to the price of the item sold. We will not be liable for any damage and/or injury that results from any use of used auto parts and/or any other item sold by any entity operated by Flagship One, Inc and the Buyer hereby now and forever relinquishes FlagshipOne, Inc. from any such liability. Electrical parts are tested before being sold and if returned, all units will be inspected for burnt components, physical damage and/or water damage; any such damage will void all Warranties. Warranty is also void if the item is misused, abused, modified, opened, tampered with, used for any purpose not originally intended, installation takes place outside of the United States, vehicle is involved in an accident and/or the part is sold to someone other than the listed Buyer. Warranty is not transferable. Buyer is solely responsible for making the correct interchange research. All interchange information provided by FlagshipOne, Inc. is speculative and must be verified by Buyer. Returned items must be in original condition and untampered with. Except as otherwise stated herein, all returned items are subject to a 20% restocking fee, plus the original shipping charge if the item(s) are purchased in error, Buyer’s remorse, vehicle was misdiagnosed and/or the item(s) are tested and deemed good by FlagshipOne, Inc. Items that are programmed and returned are subject to an $85 non-refundable programming fee. Items purchased with keys and returned are subject to a $90 non-refundable key fee in addition to the $85 non-refundable programming fee. All returns for money back must be received by FlagshipOne, Inc within 30 days from the date of the original purchase. All returns made after 30 days from the date of the original purchase include an option for an exchange or store credit—NO EXCEPTIONS. In the event an item is purchased on Ebay and is returned after 45 days from the date of the original purchase, Buyer is solely responsible for the commission charged by Ebay for that particular sale except when FlagshipOne, Inc has made an error in the listing which the Buyer relied upon prior to purchase. In the event that a unit is believed to be faulty, FlagshipOne, Inc expressly reserves the right to have the unit sent back to its facility for testing prior to replacement. FlagshipOne, Inc will NOT under any circumstances reimburse any fees a Buyer expends in connection with a possible faulty unit including, but not limited to Locksmith fees, diagnostic fees, rental car fees, storage fees, dealership fees, third party reprogramming fees, etc. The warranty includes only one claim. After one claim, the warranty is exhausted. Buyer acknowledges, agrees and accepts all the terms set forth herein upon purchase.</span>
                <table>
                  <tr>
                    <td width="50%" class="py-0">
                      <label
                        for="sku-price"
                        class="col-form-label col-form-label-sm text-red font-weight-700"
                      >To accept this return, sign here and return*</label>
                    </td>
                    <td class="py-0 pb-2">
                      <div @click="openSignaturePad" v-if="img" class="sign-img">
                        <img :src="imgUrl(img)" width="200px" height="100px" />
                      </div>
                      <div v-else @click="openSignaturePad" class="sign-here">Sign Here</div>
                    </td>
                  </tr>
                </table>
                <table>
                  <tr>
                    <td width="40%" class="py-0">
                      <label
                        for="sku-price"
                        class="col-form-label col-form-label-sm text-red font-weight-700 font-size-18"
                      >Print Name:</label>
                    </td>
                    <td class="py-0">
                      <input
                        type="text"
                        id="name"
                        name="name"
                        v-model="RMAdata.rmaformdata.name"
                        class="form-control form-control-sm"
                      />
                    </td>
                  </tr>
                </table>
              </div>
              <div class="return-content">
                <p class="content-1">PLEASE RETURN UNIT TO:</p>
                <p class="content-2">
                  Return address:
                  <br />
                  {{ RMAdata.account.account_id }}
                  <br />Flagship One Inc.
                  <br />19 Wilbur Street
                  <br />Lynbrook NY 11563
                </p>
              </div>
            </div>
          </div>
        </div>
        <signature-pad v-if="has_modal" :class-name="getStyle" @save="saveChange" @close="close" />
      </div>
    </div>
    <div v-else>{{ redirectTo404 }}</div>
  </div>
</template>

<script>
import SignaturePad from './SignaturePad';
import api from '../../api';

export default {
  name: 'RMAForm',

  components: {
    SignaturePad,
  },

  data() {
    return {
      has_modal: false,
      img: '',
      RMAdata: {
        account: {},
        customermetadata: {},
        rmaformdata: {
          originalProblem: '',
          stepsTaken: '',
          problemExperienced: '',
          stepsTakenForPart: '',
          additionalNotes: '',
          name: '',
        },
      },
      signImgForUpdate: '',
      signImgForUpdateBase64: '',
      token: '',
      isSuccess: true,
      isSuccessMsg: ''
    };
  },

  mounted() {
    let path = window.location.pathname;
    let segments = path.split('/');
    this.token = segments[4];
    //this.formId = segments[5];
    this.getRMAdata();
  },

  computed: {
    getStyle() {
      return (
        (this.headerColor || 'bg-gray-700 theme-color ') +
        (this.headerTextColor || 'text-light')
      );
    },
    redirectTo404() {
      alert(this.isSuccessMsg);
      window.location.href = '/404.html';
    },
  },

  methods: {
    imgUrl(img) {
      var base64regex = /^([0-9a-zA-Z+/]{4})*(([0-9a-zA-Z+/]{2}==)|([0-9a-zA-Z+/]{3}=))?$/;
      if (base64regex.test(img.split(',')[1])) {
        return img;
      } else {
        return `/images/CustomerSignature/${img}`;
      }
    },
    getRMAdata() {
      let obj = {
        token: this.token,
      };

      api
        .GetRMADataByToken(obj)
        .then(res => {
          if (res.data.IsSuccess) {
            this.RMAdata.account = res.data.Data.account;
            this.RMAdata.customermetadata = res.data.Data.customermetadata;
          } else {
            this.isSuccess = false;
            this.isSuccessMsg = res.data.Message;
          }
        })
        .catch(err => {});
    },
    saveRMAForm() {
      if (this.signImgForUpdateBase64 != '') {
        let obj = {
          CustomerSignature: this.signImgForUpdateBase64,
          FromData: {
            token: this.token,
            original_problem: this.RMAdata.rmaformdata.originalProblem,
            steps_taken_to_diagnose_problem: this.RMAdata.rmaformdata
              .stepsTaken,
            problems_with_part: this.RMAdata.rmaformdata.problemExperienced,
            steps_taken_to_diagnose_with_part: this.RMAdata.rmaformdata
              .stepsTakenForPart,
            additional_notes: this.RMAdata.rmaformdata.additionalNotes,
            customer_signature: this.signImgForUpdate,
            customer_name: this.RMAdata.rmaformdata.name,
            form_id: this.formId ? this.formId : '',
          },
        };
        let headers = {
          'content-type': 'multipart/form-data', // do not forget this
        };
        api
          .CreateRmaForms(obj, headers)
          .then(res => {
            if (res.data.IsSuccess) {
              this.RMAdata.rmaformdata.originalProblem =
                res.data.Data.original_problem;
              this.RMAdata.rmaformdata.stepsTaken =
                res.data.Data.steps_taken_to_diagnose_problem;
              this.RMAdata.rmaformdata.problemExperienced =
                res.data.Data.problems_with_part;
              this.RMAdata.rmaformdata.stepsTakenForPart =
                res.data.Data.steps_taken_to_diagnose_with_part;
              this.RMAdata.rmaformdata.additionalNotes =
                res.data.Data.additional_notes;
              this.RMAdata.rmaformdata.name = res.data.Data.customer_name;
              this.img = res.data.Data.customer_signature;
              this.$toast.open({
                message: 'RMA Form submitted successfully!',
                position: 'top',
              });
            } else {
              this.$toast.open({
                message: 'Something went wrong!',
                position: 'top',
                type: 'error',
              });
            }
          })
          .catch(err => {});
      } else {
        this.$toast.open({
          message: 'Signature can not be blank!',
          position: 'top',
          type: 'error',
        });
      }
    },
    backToEdit() {
      window.location.href = '/accounts/' + this.accountId + '/edit';
    },
    openSignaturePad() {
      this.has_modal = true;
    },
    close() {
      this.has_modal = false;
    },
    saveChange(data) {
      this.signImgForUpdateBase64 = data;
      this.img = data;
      this.has_modal = false;
    },
  },
};
</script>

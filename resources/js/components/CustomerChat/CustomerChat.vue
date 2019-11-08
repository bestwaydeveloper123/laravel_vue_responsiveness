<template>
  <div class="card">
    <div class="card-header bg-gray-700 py-1 text-light theme-color">
      <strong><i class="fa fa-edit"></i> Chat with Customer</strong>
    </div>
    <div class="card-body">
      <div class="container-fluid">
        <div :class="isChatting ? 'row' : ''">
          <div :class="isChatting ? 'col-auto' : ''">
            <table class="table table-sm table-borderless">
              <thead>
                <th>User ID</th>
                <th>Name</th>
                <th>Email Address</th>
                <th>Phone</th>
                <th>Question</th>
                <th>VIN</th>
                <th>Department</th>
                <th>Action</th>
              </thead>
              <tbody v-if="numberOfCustomers > 0">
                <tr v-for="(customer, index) in GET_CUSTOMERS" :key="index">
                  <td>{{ customer.userId }}</td>
                  <td>{{ customer.submit.customerName }}</td>
                  <td>{{ customer.submit.customerEmail }}</td>
                  <td>{{ customer.submit.customerPhone }}</td>
                  <td>{{ customer.submit.customerQuestion }}</td>
                  <td>{{ customer.submit.customerVin }}</td>
                  <td>{{ customer.submit.department == 'dept-1' ? 'Sales Inquiry' : 'Existing Order Information' }}</td>
                  <td><button class="btn btn-sm btn-outline btn-success" @click="acceptChat(customer)"><i class="fa fa-arrow-right"></i></button></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-if="isChatting" class="col">
            <div style="width: 300px;">Here is chat panel</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
  name: 'CustomerChat',
  data() {
    return {
      acceptedCustomer: {},
      isChatting: false,
    };
  },
  computed: {
    ...mapGetters([
      'GET_CUSTOMERS',
    ]),
    numberOfCustomers() {
      return this.GET_CUSTOMERS.length;
    },
  },
  methods: {
    acceptChat(customer) {
      Object.assign(this.acceptedCustomer, customer);
      this.isChatting = true;
    },
  },
};
</script>

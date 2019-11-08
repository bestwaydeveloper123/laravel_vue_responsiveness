<template>
<div class="overflow-auto">
  <table class="table table-sm table-hover inventory-fonts">
    <thead>
      <tr>
        <th v-if="orderType=='Website'" scope="col" rowspan="2" class="head-column">Team</th>
        <th scope="col" rowspan="2" class="head-column">Record #</th>
        <th scope="col" rowspan="2" class="head-column">Account Name</th>
        <th scope="col" rowspan="2" class="head-column">Order Type</th>
        <th scope="col" rowspan="2" class="head-column">Date Purchased</th>
        <th v-if="orderType=='Website'" scope="col" rowspan="2" class="head-column">Magento ID</th>
        <th v-if="orderType=='eBay- fs1inc'" scope="col" rowspan="2" class="head-column">eBay ID</th>
        <th v-if="orderType=='eBay- fs1inc'" scope="col" rowspan="2" class="head-column">eBay User</th>
        <th scope="col" rowspan="2" class="head-column">Item Purchased</th>
        <th scope="col" rowspan="2" class="head-column">Price Paid</th>
        <th v-if="orderType=='Website'" scope="col" rowspan="2" class="head-column">Customer VIN</th>

        <th scope="col" class="head-column">Email</th>
        <th scope="col" class="head-column">Phone</th>
        <th scope="col" class="head-column">Ship to Name</th>
        <th scope="col" class="head-column">Street</th>
        <th scope="col" class="head-column">City</th>
        <th scope="col" class="head-column">Region</th>
        <th scope="col" class="head-column">Country</th>
        <th scope="col" class="head-column">Postal Code</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(info, id) in bulkInfo" :key="id">
        <td v-if="orderType=='Website'" class="body-column">{{ info.record_owner }}</td>
        <td class="body-column">{{ info.record_number }}</td>
        <td class="body-column">{{ info.account_name }}</td>
        <td class="body-column">{{ info.order_type }}</td>
        <td class="body-column">{{ info.created_at }}</td>
        <td v-if="orderType=='Website'" class="body-column">{{ info.magento_id }}</td>
        <td v-if="orderType=='eBay- fs1inc'" class="body-column">{{ info.ebayorder_id }}</td>
        <td v-if="orderType=='eBay- fs1inc'" class="body-column">{{ info.ebayusername }}</td>
        <td class="body-column">{{ info.item_purchased }}</td>
        <td class="body-column">{{ info.price_paid }}</td>
        <td v-if="orderType=='Website'" class="body-column">{{ info.customer_vin }}</td>
        <td class="body-column">{{ info.customer_email }}</td>
        <td class="body-column">{{ info.customer_phone }}</td>
        <td class="body-column">{{ info.ship_to_name }}</td>
        <td class="body-column">{{ info.customer_street }}</td>
        <td class="body-column">{{ info.customer_city }}</td>
        <td class="body-column">{{ info.customer_state }}</td>
        <td class="body-column">{{ info.customer_country }}</td>
        <td class="body-column">{{ info.customer_zipcode }}</td>
      </tr>
    </tbody>
  </table>
</div>
</template>

<script>
export default {
  name: 'BulkInfoTable',
  props: ['order-type', 'bulk-info'],
}
</script>

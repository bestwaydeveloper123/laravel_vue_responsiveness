/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('@coreui/coreui');
require('./bootstrap');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

import VueNativeSock from 'vue-native-websocket';

import store from './store/store';
import {
  SOCKET_ONOPEN,
  SOCKET_ONCLOSE,
  SOCKET_ONERROR,
  SOCKET_ONMESSAGE,
  SOCKET_RECONNECT,
  SOCKET_RECONNECT_ERROR,
} from './store/socket-mutation-types';

const mutations = {
  SOCKET_ONOPEN,
  SOCKET_ONCLOSE,
  SOCKET_ONERROR,
  SOCKET_ONMESSAGE,
  SOCKET_RECONNECT,
  SOCKET_RECONNECT_ERROR,
};

window.Vue = require('vue');

// Vue.use(VueNativeSock, 'ws://localhost:8090', {
//   store,
//   mutations,
//   format: 'json',
//   reconnection: true,
//   reconnectionAttempts: 5,
//   reconnectionDelay: 3000,
// });

Vue.component(
  'customer-information',
  require('./components/CustomerInformation/CustomerInformation.vue').default,
);
Vue.component(
  'order-information',
  require('./components/OrderInformation/OrderInformation.vue').default,
);
Vue.component(
  'programming-information',
  require('./components/ProgrammingInformation/ProgrammingInformation.vue').default,
);
Vue.component(
  'trix-pro-editor',
  require('./components/TrixProEditor/TrixProEditor.vue').default,
);
Vue.component(
  'part-information',
  require('./components/PartInformation/PartInformation.vue').default,
);
Vue.component(
  'address-edit-card',
  require('./components/AddressEditCard/AddressEditCard.vue').default,
);
Vue.component(
  'order-status',
  require('./components/OrderStatus/OrderStatus.vue').default,
);
Vue.component(
  'vendor-edit',
  require('./components/VendorCard/VendorEdit.vue').default,
);
Vue.component(
  'scanner-match',
  require('./components/ScannerMatch/ScannerMatch.vue').default,
);
Vue.component(
  'search-inventory',
  require('./components/SearchInventory/SearchInventory.vue').default,
);
Vue.component(
  'search-core',
  require('./components/SearchInventory/SearchCore.vue').default,
);
Vue.component(
  'part-location',
  require('./components/OrderStatus/PartLocation.vue').default,
);
Vue.component(
  'programming-entry',
  require('./components/ProgrammingEntry/ProgrammingEntry.vue').default,
);
Vue.component(
  'customer-tracking',
  require('./components/CustomerTracking/CustomerTracking.vue').default,
);
Vue.component(
  'price-information',
  require('./components/PriceInformation/PriceInformation.vue').default,
);
Vue.component(
  'team-settings',
  require('./components/TeamSettings/TeamSettings.vue').default,
);
Vue.component(
  'image-options',
  require('./components/Image/ImageOptions.vue').default,
);
Vue.component(
  'image-view',
  require('./components/Image/ImageView.vue').default,
);
Vue.component(
  'team-settings',
  require('./components/TeamSettings/TeamSettings.vue').default,
);
Vue.component(
  'scanner-table',
  require('./components/ScannerTable/ScannerTable.vue').default,
);
Vue.component(
  'shipping-page',
  require('./components/ShippingPage/ShippingPage.vue').default,
);
Vue.component(
  'shipping-user',
  require('./components/ShippingPage/ShippingUser.vue').default,
);
Vue.component(
  'breadcrumbs-menu',
  require('./components/BreadcrumbsMenu/BreadcrumbsMenu.vue').default,
);
Vue.component(
  'meta-info',
  require('./components/MetaInfo/MetaInfo.vue').default,
);
Vue.component(
  'program-page',
  require('./components/ProgrammerPage/ProgramPage.vue').default,
);
Vue.component(
  'program-user',
  require('./components/ProgrammerPage/ProgramUser.vue').default,
);
Vue.component(
  'shipping-search',
  require('./components/ShippingSearch/ShippingSearch.vue').default,
);
Vue.component(
  'account-manager-page',
  require('./components/AccountManagerPage/AccountManagerPage.vue').default,
);
Vue.component(
  'account-manager-point',
  require('./components/AccountManagerPage/AccountManagerPoint.vue').default,
)
Vue.component(
  'docusign',
  require('./components/Docusign/Docusign.vue').default,
);
Vue.component(
  'docusign-view',
  require('./components/Docusign/DocusignView.vue').default,
);
Vue.component(
  'rmaform',
  require('./components/RMAForm/RMAForm.vue').default,
);
Vue.component(
  'rmaform-view',
  require('./components/RMAForm/RMAFormView.vue').default,
);
Vue.component(
  'patch-notes',
  require('./components/PatchNotes/PatchNotes.vue').default,
);
Vue.component(
  'attribute-card',
  require('./components/AttributeCard/AttributeCard.vue').default,
);
Vue.component(
  'secondary-manager-points',
  require('./components/SecondaryManagerPoints/SecondaryManagerPoints.vue').default,
);
Vue.component(
  'bulk-add-account',
  require('./components/BulkAddAccount/BulkAddAccount.vue').default,
);
Vue.component(
  'stock-information',
  require('./components/StockInformation/StockInformation.vue').default,
);
Vue.component(
  'ebay-website-breakdown',
  require('./components/EbayWebsiteBreakDown/EbayWebsiteBreakDown.vue').default,
);
Vue.component(
  'customer-chat',
  require('./components/CustomerChat/CustomerChat.vue').default,
);

const app = new Vue({
  el: '#vue-app',
  store,
});

import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/index.css';
Vue.use(VueToast);

Vue.prototype.$eventHub = new Vue();

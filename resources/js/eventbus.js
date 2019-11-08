import Vue from 'vue';
const eventBus = new Vue();
export default eventBus;
Vue.prototype.$eventHub = new Vue();
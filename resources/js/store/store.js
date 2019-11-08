import Vue from 'vue';
import Vuex from 'vuex';

import {
  SOCKET_ONOPEN,
  SOCKET_ONCLOSE,
  SOCKET_ONERROR,
  SOCKET_ONMESSAGE,
  SOCKET_RECONNECT,
  SOCKET_RECONNECT_ERROR,
} from './socket-mutation-types';

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    // chatting status: ['INIT', 'HASID', 'WAITING', 'CHATTING', 'CLOSED']
    selfId: null,
    status: 'INIT',
    customers: [],
    messages: [],
    socket: {
      isConnected: false,
      reconnectError: false,
    },
  },
  getters: {
    IS_CONNECTED: state => state.socket.isConnected,

    GET_STATUS: state => state.status,

    GET_LOCAL_ID: state => state.selfId,

    GET_CUSTOMERS: state => state.customers,
  },
  actions: {
    SEND_MESSAGE: (context, message) => {
      Vue.prototype.$socket.sendObj(message);
    },
  },
  mutations: {
    // mutations for socket
    [SOCKET_ONOPEN](state, payload) {
      state.socket.isConnected = true;
      console.info(payload);
      let userId = localStorage.getItem('local-id');
      if (!userId) {
        userId = Math.floor(Math.random() * 10000).toString();
        userId += Math.floor(Math.random() * 10000).toString();
        userId += Math.floor(Math.random() * 10000).toString();
        userId += Math.floor(Math.random() * 10000).toString();
      }
      Vue.prototype.$socket.sendObj({
        command: 'INIT:SUPPORTER',
        userId,
      });
    },
    [SOCKET_ONCLOSE](state, payload) {
      state.socket.isConnected = false;
      console.info(payload);
    },
    [SOCKET_ONERROR](state, payload) {
      console.error(state, payload);
    },
    [SOCKET_ONMESSAGE](state, message) {
      switch (message.command) {
        case 'INIT:SUPPORTER':
          state.selfId = message.userId;
          localStorage.setItem('local-id', message.userId);
          state.status = 'HASID';
          break;
        case "CUSTOMER_REQUEST":
          state.customers = message.request;
          break;
        default: break;
      }
    },
    [SOCKET_RECONNECT](state, count) {
      console.info(state.socket, count);
    },
    [SOCKET_RECONNECT_ERROR](state) {
      state.socket.reconnectError = true;
    },
    // mutations for chat client
  },
});

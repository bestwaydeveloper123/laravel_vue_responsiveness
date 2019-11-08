<template>
  <transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container" style="overflow-y: initial !important;">
          <div class="card mb-1 border-none">
            <div class="card-header py-1" :class="className">
              <i class="fa fa-home"></i>Order History
            </div>
            <div class="card-body p-0 my-1" style="height: 400px;overflow-y: auto;">
              <p v-if="!hasHistory" class="text-center py-2 my-3 bg-gray-200">- No data available -</p>
              <table v-else class="table table-bordered m-b-0">
                <tr v-for="(item, index) in historyData" class="p-0" :key="index" width="80%">
                  <td class="py-1">
                    <button
                      type="button"
                      class="btn btn-pill btn-sm pb-0 pt-0 theme-color"
                      :class="teamButton"
                    >{{ roleName }}</button>
                  </td>
                  <td class="py-1">{{ item.created_by }}</td>
                  <td class="py-1">
                    <div class="text-truncate" v-html="showItem(item)"></div>
                  </td>
                  <td class="py-1">{{ item.created_at }}</td>
                  <td v-if="roleName == 'shipping' && item.team == 'shipping'" class="py-1">
                    <button
                      @click="deleteStatus(item.id)"
                      type="button"
                      class="btn btn-danger"
                    >Delete</button>
                  </td>
                </tr>
              </table>
            </div>
          </div>
          <div class="modal-footer py-0 mt-1 bg-gray-300 theme-color">
            <button class="btn btn-secondary" type="button" @click="close">Close</button>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
import api from '../../api';
import { isDate } from 'util';
export default {
  props: {
    className: {
      type: String,
      default: null,
    },
    accountTeam: {
      type: String,
      default: '',
    },
    roleName: {
      type: String,
      default: '',
    },
    accountId: {
      type: Number,
    },
  },
  data() {
    return {
      historyData: [],
      isDelete: false,
    };
  },
  created() {
    window.axios
      .get('/api/get-all-order-status', {
        params: { id: this.accountId },
      })
      .then(res => {
        this.historyData = res.data;
        window._.assign(this.historyData, res.data);
      });
  },
  computed: {
    teamButton() {
      if (this.roleName.toLowerCase() == 'admin') {
        return 'bg-orange white-font';
      } else if (this.roleName.toLowerCase() == 'shipping') {
        return 'bg-gray-900 white-font';
      } else if (this.roleName.toLowerCase() == 'programming') {
        return 'bg-gray white-font';
      } else if (this.accountTeam == 'Yellow Team') {
        return 'bg-yellow white-font';
      } else if (this.accountTeam == 'Green Team') {
        return 'bg-green white-font';
      } else if (this.accountTeam == 'Gold Team') {
        return 'bg-gold white-font';
      } else if (this.accountTeam == 'Purple Team') {
        return 'bg-purple white-font';
      } else if (this.accountTeam == 'Pink Team') {
        return 'bg-pink white-font';
      }
    },
    hasHistory() {
      return this.historyData.length !== 0;
    },
  },
  methods: {
    deleteStatus(id) {
      let obj = {
        id: id,
      };
      api
        .deleteOrderStatus(obj)
        .then(res => {
          this.historyData = res.data;
          this.isDelete = true;
        })
        .catch(err => console.log(err));
    },
    close() {
      if (this.isDelete) {
        location.reload();
      }
      this.$emit('close');
    },
    showItem(item) {
      return (
        (item.orderstatus ? item.orderstatus + '<br>' : '') +
        (item.orderstatuscustom ? item.orderstatuscustom : '')
      );
    },
  },
};
</script>

<style scoped>
.border-none {
  border: none !important;
}

.m-b-0 {
  margin-bottom: 0 !important;
}

.white-font {
  color: #ffffff !important;
}

.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: table;
  transition: opacity 0.3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: top;
}

.modal-container {
  width: 65%;
  margin: 50px auto;
  padding: 0px 0px 3px 0px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
  transition: all 0.3s ease;
  font-family: Helvetica, Arial, sans-serif;
}

.modal-header h3 {
  margin-top: 0;
  color: #42b983;
}

.modal-body {
  margin: 20px 0;
}

.modal-default-button {
  float: right;
}

/*
 * The following styles are auto-applied to elements with
 * transition="modal" when their visibility is toggled
 * by Vue.js.
 *
 * You can easily play with the modal transition by editing
 * these styles.
 */

.modal-enter {
  opacity: 0;
}

.modal-leave-active {
  opacity: 0;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}
</style>


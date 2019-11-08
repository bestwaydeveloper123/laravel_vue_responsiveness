<template>
  <transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">
          <div class="card mb-1">
            <div class="card-header pt-1 pb-1" :class="className">
              <i class="fa fa-bell"></i> Ping 
            </div>
            <div class="modal-body pt-0 pb-0 pl-0 pr-0 mt-1 mb-1">
              <table
                class="table table-bordered table-striped table-responsive-sm table-sm pull-left mb-0"
                width="2/3"
              >
                <tbody>
                  <tr>
                    <td class="pt-0 pb-0" width="10%">
                      <label
                        class="col-form-label col-form-label-sm"
                        for="text-input"
                        >Send to</label
                      >
                    </td>
                    <td class="pt-0 pb-0" width="40%">
                      <select
                        class="form-control form-control-sm"
                        width="100%"
                        type="text"
                        v-model="sendTo"
                      >
                        <option disabled value selected>Select</option>
                        <option v-for="(user) in allUsers" :key="user.id" :value="user.username"
                          >{{user.username}} - ({{ user.email }})</option>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td class="pt-0 pb-0" width="10%">
                      <label
                        class="col-form-label col-form-label-sm"
                        for="text-custom"
                        >Note</label
                      >
                    </td>
                    <td class="pt-0 pb-0" width="40%">
                      <textarea
                        class="form-control form-control-sm"
                        id="text-custom"
                        rows="3"
                        v-model="note"
                      ></textarea>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="modal-footer pt-0 pb-0 mt-1 bg-gray-300 theme-color">
              <button class="btn btn-secondary" type="button" @click="close">
                Close
              </button>
              <button class="btn btn-success" type="button" @click="saveChange">
                Send
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
import api from '../../api';

export default {
  props: {
    className: {
      type: String,
      default: null,
    },
  },

  data() {
    return {
      allUsers: '',
      sendTo:'',
      note:''
    };
  },

  mounted(){
    this.getUser();
  },

  computed: {
  },

  methods: {
    close() {
      this.$emit('close');
    },
    getUser(){
      api
        .GetPingemployees()
        .then(res => {
          if (res.data.IsSuccess) {
            this.allUsers = res.data.Data.Users;
          }
        })
        .catch(err => {});
    },
    saveChange() {
      let obj = {
        send_to: this.sendTo,
        note: this.note,
        account_id: localStorage.getItem('accountId'),
      }

      api
        .PostPingemployees(obj)
        .then(res => {
          if (res.data.IsSuccess) {
            this.$emit('close');
          }
        })
        .catch(err => {});
    },
  },
};
</script>

<style scoped>
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
  width: 50%;
  margin: 30px auto;
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

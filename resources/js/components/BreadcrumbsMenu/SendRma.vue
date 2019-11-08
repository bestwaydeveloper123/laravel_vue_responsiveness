<template>
  <transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">
          <div class="card mb-1">
            <div class="card-header pt-1 pb-1" :class="className">
              <i class="fa fa-envelope"></i>Send RMA Form
            </div>
            <div class="modal-body pt-0 pb-0 pl-0 pr-0 mt-1 mb-1">
              <table
                class="table table-bordered table-striped table-responsive-sm table-sm pull-left mb-0"
                width="2/3"
              >
                <tr>
                  <td class="pt-2 pb-2" width="10%">
                    <label class="col-form-label col-form-label-sm">Select E-Mail</label>
                  </td>
                  <td class="pt-2 pb-2" width="40%">
                    <select v-model="email" class="form-control form-control-sm">
                      <option :value="email1">{{this.email1}}</option> 
                      <option :value="email2">{{this.email2}}</option>
                    </select>
                  </td>
                </tr>
              </table>
            </div>
            <div class="modal-footer pt-0 pb-0 mt-1 bg-gray-300 theme-color">
              <button class="btn btn-secondary" type="button" @click="close">Close</button>
              <button class="btn btn-success" type="button" @click="sendMail">Send</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  props: {
    className: {
      type: String,
      default: null,
    },
    email1: {
      type: String,
    },
    email2: {
      type: String,
    },
  },

  data() {
    return {
      email: ''
    };
  },

  methods: {
    close() {
      this.$emit('close');
    },
    sendMail() {
      this.$emit('save', this.email);
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
  width: 35%;
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

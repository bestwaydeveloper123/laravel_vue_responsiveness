<template>
  <transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container" style="overflow-y: initial !important;">
          <div class="card mb-1 border-none">
            <div class="card-header pt-1 pb-1" :class="className">
              <i class="fa fa-history"></i>Last Modified History
            </div>
            <div class="card-body pt-0 pb-0 pl-0 pr-0 mt-1 mb-1"  style="height: 400px;overflow-y: auto;">
              <table class="table table-bordered m-b-0" style="text-align:center">
              <thead>
                <tr>
                  <th>
                    User
                  </th>
                  <th>
                    Date
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in data" class="p-0" :key="index" id="info">
                  <td class="pt-1 pb-1" style="width:48% !important">
                    <div class="text-truncate" v-html="item.secondary_manager"></div>
                  </td>
                  <td class="pt-1 pb-1" style="width:49% !important">
                    <div class="text-truncate" v-html="item.created_at"></div>
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer pt-0 pb-0 mt-1 bg-gray-300 theme-color">
            <button class="btn btn-secondary" type="button" @click="close">Close</button>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  props: {
    data: {
      type: Array,
      default: [],
    },
    className: {
      type: String,
      default: null,
    },
  },
  methods: {
    close() {
      this.$emit('close');
    },

    showItem(item) {
      return (
        (item.secondary_manager ? item.secondary_manager + '<br>' : '') +
        (item.created_at ? item.created_at : '')
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
  height: 10%;
  background-color: rgba(0, 0, 0, 0.5);
  display: table;
  transition: opacity 0.3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: top;
}

.modal-container {
  width: 30%;
  margin: 5% auto 25% auto;
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

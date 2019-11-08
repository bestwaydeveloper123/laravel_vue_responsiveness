<template>
  <transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">
          <div class="card mb-1">
            <div class="card-header py-1" :class="headerStyle">
              <i class="fa fa-edit"></i> VIN Match
            </div>
            <div class="modal-body p-0 my-1">
              <table
                class="table table-bordered table-striped table-responsive-sm table-sm pull-left mb-0"
                width="2/3"
              >
                <tbody>
                  <tr>
                    <td>
                      <label class="col-form-label col-form-label-sm">
                        <strong>Possible Quote Already In Hydra2</strong>
                      </label>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="modal-footer py-0 mt-1 bg-gray-300 theme-color">
              <button class="btn btn-success" type="button" @click="redirectAccount">Go to Account</button>
              <button class="btn btn-secondary" type="button" @click="close">Close</button>
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
    accountId: Number,
    headerStyle: String,
  },
  data() {
    return {};
  },
  mounted() {},
  methods: {
    redirectAccount() {
      console.log(this.accountId)
      if (this.accountId) {
        window.location.href = '/accounts/' + this.accountId + '/edit';
      }
    },
    close() {
      this.$emit('close');
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
  transition: opacity 0.5s ease;
}
.modal-wrapper {
  display: table-cell;
  vertical-align: top;
}
.modal-container {
  width: 40%;
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



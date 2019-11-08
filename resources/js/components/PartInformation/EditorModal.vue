<template>
  <transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">
          <div class="card-header card-header pt-1 pb-1 bg-gray-700 theme-color text-light">
            <i class="fa fa-cog"></i>OnBoard Testing
          </div>
          <div class="modal-body pt-0 pb-0 pl-0 pr-0 mb-1">
            <table
              id="account_table"
              class="table table-bordered table-striped table-responsive-sm table-sm mb-0"
              width="2/3"
            >
              <tr>
                <td>
                  <div class="custom-control custom-switch">
                    <input
                      type="checkbox"
                      class="custom-control-input cursor-pointer"
                      id="onBoardTesting"
                      v-model="onBoardData.onBoard"
                    >
                    <label class="custom-control-label" for="onBoardTesting">On Board Testing REQUIRED</label>
                  </div>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="custom-control custom-switch">
                    <input
                      type="checkbox"
                      class="custom-control-input cursor-pointer"
                      id="sff"
                      v-model="onBoardData.saveForFuture"
                    >
                    <label class="custom-control-label" for="sff">Save For Future</label>
                  </div>
                </td>
              </tr>
            </table>
          </div>
          <div class="modal-footer pt-0 pb-0 mt-1 bg-gray-300 theme-color">
            <button class="btn btn-secondary" type="button" @click="close">Close</button>
            <button class="btn btn-success" type="button" @click="saveChange">Save changes</button>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
export default {

  props: {
    onBoardValue: Boolean,
    pcmHWV: String,
  },

  data() {
    return {
      onBoardData: {
        onBoard: false,
        saveForFuture: false,
        //pcmHWValue: this.pcmHWV
      }
    };
  },
  mounted(){
    if(this.onBoardValue != '0') {
      this.onBoardData.onBoard = true;
    } else {
      this.onBoardData.onBoard = false;
    }
  },
  methods: {
    close() {
      this.$emit('close');
    },

    saveChange() {
      this.$emit('save', this.onBoardData);
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
  width: 70%;
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
  position: relative;
  overflow: auto;
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



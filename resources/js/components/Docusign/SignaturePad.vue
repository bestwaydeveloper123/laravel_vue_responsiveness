<template>
  <transition name="modal" id="myModal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">
          <div class="card mb-0">
            <div
              class="card-header card-header bg-gray-700 theme-color text-light pt-1 pb-1"
              :class="className"
            >
              <i class="fa fa-pencil-square-o"></i> Draw Signature
            </div>
            <div class="modal-body py-0 pl-0 pr-0 mb-0 mt-0">
              <div id="app">
                <VueSignaturePad width="514px" height="300px" ref="signaturePad" />
                <!-- <div>
                  <button @click="save">Save</button>
                  <button @click="undo">Undo</button>
                </div> -->
              </div>
            </div>
            <div class="modal-footer py-0 mt-1 bg-gray-300 theme-color">
              <button class="btn btn-secondary" type="button" @click="close">Close</button>
              <button class="btn btn-primary" type="button" @click="undo">Undo</button>
              <button class="btn btn-success" type="button" @click="save">Save</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
import VueSignaturePad from 'vue-signature-pad';

export default {
  name: 'MySignaturePad',

  props: {
    className: {
      type: String,
      default: '',
    },
  },

  methods: {
    undo() {
      this.$refs.signaturePad.undoSignature();
    },
    save() {
      const { isEmpty, data } = this.$refs.signaturePad.saveSignature();
      this.$emit('save', data);
      // console.log(isEmpty);
      // console.log(data);
    },
    close() {
      this.$emit('close');
    },
  },
};
</script>

<style scoped>
.fsize {
  font-size: 0.875rem;
}

.ml-150 {
  margin-left: 150px;
}

.pt-10 {
  padding-top: 10px !important;
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

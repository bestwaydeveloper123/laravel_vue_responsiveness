<template>
  <div class="card mb-1">
    <div class="card-header py-1 bg-gray-700 theme-color text-light">
      <a class="text-light float-left cursor-pointer vendor-title" @click.prevent="active = !active">
        <strong>
          <i class="fa fa-edit"></i>
          {{ title }}
        </strong>
        <i class="fa fa-caret-down"></i>
      </a>
      <span class="btn btn-sm badge-success float-left ml-5 py-0" @click="clickBtn('0')">
        {{ addTitle }}
        <i class="fa fa-plus"></i>
      </span>
      <span v-if="module == 'CustomerTracking'" class="btn btn-sm badge-success float-left ml-1 py-0" @click="clickBtn('1')">
        {{ addTitle }}
        OutBound <i class="fa fa-plus"></i>
      </span>
      <span v-if="module == 'CustomerTracking'" class="btn btn-sm badge-success float-left ml-1 py-0" @click="clickBtn('2')">
        {{ addTitle }}
        InBound <i class="fa fa-plus"></i>
      </span>
    </div>
    <transition-expand>
      <div class="card-body py-0" v-if="active" :class="[ (role != 3) ? '':['expand-enter','expand-leave-to'] ]">
        <slot/>
      </div>
    </transition-expand>
  </div>
</template>

<script>
import TransitionExpand from './TransitionExpand.vue';

export default {
  components: {
    TransitionExpand,
  },

  props: {
    title: String,
    toggleId: String,
    addTitle: {
      type: String,
      default: ''
    },
    role: String,
    module: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      active: true,
    };
  },
  methods: {
    clickBtn(val) {
      this.$emit('clickbtn',val);
    },
  },
};
</script>
<style scope>
.vendor-title :hover {
  text-decoration: underline !important;
}
.expand-enter-active,
.expand-leave-active {
  transition: height 0.3s ease-in-out;
  overflow: hidden;
}

.expand-enter,
.expand-leave-to {
  height: 0;
}
</style>

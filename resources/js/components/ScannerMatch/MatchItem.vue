<template>
  <tr>
    <td>{{ id }}</td>
    <!-- <td>{{ item.title }} -->
    <td>{{ doLabel }}</td>
    <td v-if="role == 1">
      <label
        class="switch switch-label switch-pill switch-outline-primary-alt switch-sm mb-0 pb-1 pt-1"
      >
        <!-- <input class="switch-input" type="hidden" name="onedayhandling" value="0"> -->
        <input class="switch-input" type="checkbox" v-bind:id="id" v-model="value" @change="check(value)">
        <span class="switch-slider" data-checked="✓" data-unchecked="✕"></span>
      </label>
    </td>
  </tr>
</template>

<script>
export default {
  props: {
    item: Object,
    role: String
  },

  data() {
    return {
      id: this.item !== undefined && this.item.id,
      value: this.item.publish,
    };
  },

  mounted() {
    this.checkValue;
  },
  computed: {
    checkValue() {
      if (this.value == 1) {
        this.value = true;
      } else {
        this.value = false;
      }
    },
    doLabel() {
      return this.value == 1 ? 'Published' : 'Deleted';
    },
  },

  methods: {
    check(val) {
        if (val == 1) {
          this.$emit('update', this.id, 1);
        } else if (val == 0) {
          this.$emit('update', this.id, 0);
        }
    },
    // publish() {
    //   this.$emit('update', this.id, 1);
    // },

    // unpublish() {
    //   this.$emit('update', this.id, 0);
    // },
  },
};
</script>

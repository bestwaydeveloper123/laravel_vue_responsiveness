<template>
  <div>
    <div>
      <div ref="prependDiv" v-if="$slots.prepend || prepend" class="input-group-prepend">
        <slot name="prepend">
          <span class="input-group-text">{{ prepend }}</span>
        </slot>
      </div>
      <input
        ref="input"
        type="search"
        :class="`form-control height-31 form-control-sm bg-gray-100 ${inputClass}`"
        :placeholder="placeholder"
        :aria-label="placeholder"
        :value="inputValue"
        @focus="isFocused = true"
        @blur="handleBlur"
        @input="handleInput($event.target.value)"
        autocomplete="off"
        :name="name"
      >
      <div v-if="$slots.append || append" class="input-group-append">
        <slot name="append">
          <span class="input-group-text">{{ append }}</span>
        </slot>
      </div>
    </div>
    <bootstrap-typeahead-list
      class="vbt-autcomplete-list"
      ref="list"
      v-show="isFocused && data.length > 0"
      :query="inputValue"
      :data="formattedData"
      :background-variant="backgroundVariant"
      :text-variant="textVariant"
      :maxMatches="maxMatches"
      :minMatchingChars="minMatchingChars"
      @hit="handleHit"
    >
      <!-- pass down all scoped slots -->
      <template
        v-for="(slot, slotName) in $scopedSlots"
        :slot="slotName"
        slot-scope="{ data, htmlText }"
      >
        <slot :name="slotName" v-bind="{ data, htmlText }"></slot>
      </template>
    </bootstrap-typeahead-list>
  </div>
</template>

<script>
import BootstrapTypeaheadList from './BootstrapTypeaheadList.vue';
import ResizeObserver from 'resize-observer-polyfill';

export default {
  name: 'BootstrapTypehead',

  components: {
    BootstrapTypeaheadList,
  },

  props: {
    size: {
      type: String,
      default: null,
      validator: size => ['lg', 'sm'].indexOf(size) > -1,
    },
    preValue: {
      type: String,
    },
    value: {
      type: String,
    },
    data: {
      type: Array,
      required: true,
      validator: d => d instanceof Array,
    },
    serializer: {
      type: Function,
      default: d => d,
      validator: d => d instanceof Function,
    },
    realValue: {
      type: Function,
      default: d => d,
      validator: d => d instanceof Function,
    },
    backgroundVariant: String,
    textVariant: String,
    inputClass: {
      type: String,
      default: '',
    },
    maxMatches: {
      type: Number,
      default: 6,
    },
    minMatchingChars: {
      type: Number,
      default: 2,
    },
    placeholder: String,
    prepend: String,
    append: String,
    name: String,
  },

  computed: {
    sizeClasses() {
      return this.size ? `input-group input-group-${this.size}` : 'input-group';
    },

    formattedData() {
      if (!(this.data instanceof Array)) {
        return [];
      }

      return this.data.map((d, i) => {
        return {
          id: i,
          data: d,
          text: this.serializer(d),
          value: this.realValue(d),
        };
      });
    },
  },

  methods: {
    resizeList(el) {
      const rect = el.getBoundingClientRect();
      const listStyle = this.$refs.list.$el.style;

      // Set the width of the list on resize
      listStyle.width = rect.width + 'px';

      // Set the margin when the prepend prop or slot is populated
      // (setting the "left" CSS property doesn't work)
      if (this.$refs.prependDiv) {
        const prependRect = this.$refs.prependDiv.getBoundingClientRect();
        listStyle.marginLeft = prependRect.width + 'px';
      }
    },

    handleHit(evt) {
      if (typeof this.value !== 'undefined') {
        this.$emit('input', evt.value);
      }

      this.inputValue = evt.value;
      this.$emit('hit', evt.data);
      this.$refs.input.blur();
      this.isFocused = false;
    },

    handleBlur(evt) {
      const tgt = evt.relatedTarget;
      if (tgt && tgt.classList.contains('vbst-item')) {
        return;
      }
      this.isFocused = false;
    },

    handleInput(newValue) {
      this.inputValue = newValue;

      // If v-model is being used, emit an input event
      if (typeof this.value !== 'undefined') {
        this.$emit('input', newValue);
      }
    },
  },

  data() {
    return {
      isFocused: false,
      inputValue: this.preValue || '',
    };
  },

  mounted() {
    this.$_ro = new ResizeObserver(e => {
      this.resizeList(this.$refs.input);
    });
    this.$_ro.observe(this.$refs.input);
    this.$_ro.observe(this.$refs.list.$el);
  },

  beforeDestroy() {
    this.$_ro.disconnect();
  },
};
</script>

<style scoped>

.height-52 {
  height: 52px;
}

.height-31 {
  height: 31px;
}

.vbt-autcomplete-list {
  padding-top: 3px;
  width: inherit;
  position: absolute;
  max-height: 250px;
  overflow-y: auto;
  z-index: 999;
}
</style>

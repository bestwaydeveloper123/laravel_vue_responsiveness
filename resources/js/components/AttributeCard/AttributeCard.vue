<template>
  <div class="card mb-1">
    <div class="card-header py-1" :class="getHeaderStyle">
      <strong><i class="fa fa-ellipsis-h" aria-hidden="true"></i> Attribute</strong>
    </div>
    <div class="card-body py-1">
      <div class="d-flex flex-row">
        <div
          v-for="(icon, index) in icons"
          :key="index"
          class="mr-3"
        >
          <i
            :class="`${icon.color} ${icon.icon}`"
            class="fa fa-2x text-light theme-color icon-attribute"
            :title="icon.title"></i>
        </div>
        <i
          v-if="showLevelIcon"
          class="fa fa-2x text-light theme-color bg-primary icon-attribute-level"
          :title="showLevelIcon.title"
        >
          {{ showLevelIcon.icon }}
        </i>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AttributeCard',
  props: {
    account: Object,
    customer: Object,
    tracking: {
      type: Array,
      default: null,
    },
    headerStyle: String,
  },
  data() {
    return {
      icons: [],
    }
  },
  mounted() {
    if (this.account) {
      const {
        ordertype,
        itempurchased,
        programmingdetails,
        onedayhandling,
        sticker,
        fixplugorcase,
        changelabel,
        removebracket,
        wrongpart,
        prog_mods,
      } = this.account;
      if (ordertype == 'Phone - Pickup') {
        this.icons.push({
          color: 'bg-secondary',
          icon: 'fa-phone',
          title: 'Phone - Pickup',
        });
      }
      const lowerStr = itempurchased && itempurchased.toLowerCase();
      if (lowerStr && lowerStr.indexOf('keys') !== -1) {
        this.icons.push({
          color: 'bg-red',
          icon: 'fa-key',
          title: 'Item has keys.',
        });
      }
      const progDetail = programmingdetails && programmingdetails.trim();
      if (progDetail) {
        this.icons.push({
          color: 'bg-info',
          icon: 'fa-info',
          title: 'Program has details.',
        });
      }
      if (onedayhandling
        || sticker
        || fixplugorcase
        || changelabel
        || removebracket
        || wrongpart
        || prog_mods)
      {
        let title = onedayhandling ? '1 day handling\r\n' : '';
        title += sticker ? 'sticker\r\n' : '';
        title += fixplugorcase ? 'fix plug/case\r\n' : '';
        title += changelabel ? 'change label\r\n' : '';
        title += removebracket ? 'remove bracket\r\n' : '';
        title += wrongpart ? 'wrong part\r\n' : '';
        title += prog_mods ? 'programming mod' : '';
        title = title.trim();
        this.icons.push({
          color: 'bg-primary',
          icon: 'fa-check',
          title,
        });
      }
    }
    if (this.tracking) {
      for(let i = 0; i < this.tracking.length; i ++) {
        if (this.tracking[i].warrantyexhausted) {
          this.icons.push({
            color: 'bg-warning',
            icon: 'fa-leaf',
            title: 'Warranty exhausted.',
          });
          break;
        }
      }
    }
  },
  computed: {
    getHeaderStyle() {
      return this.headerStyle || 'bg-gray-700 theme-color text-light';
    },
    showLevelIcon() {
      if (this.customer) {
        const { level } = this.customer;
        if (level == 'Plus (2+ sales)') {
          return {
            icon: 'P1',
            title: level,
          };
        } else if (level == 'Premier (3+ sales)') {
          return {
            icon: 'P2',
            title: level,
          };
        } else if (level == 'Platinum (5+ sales)') {
          return {
            icon: 'P3',
            title: level,
          };
        }
        return false;
      }
    },
  }
}
</script>

<style scoped>
.icon-attribute {
  padding: 5px;
  margin: 4px;
  border-radius: 5px;
  width: 42px;
  height: 42px;
  text-align: center;
}
.icon-attribute-level {
  padding-top: 4px;
  margin: 4px;
  border-radius: 5px;
  text-align: center;
  width: 42px;
  height: 42px;
  cursor: default;
}
</style>


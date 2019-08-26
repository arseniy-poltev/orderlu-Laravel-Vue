<template>
  <div class="btn-group">
    {{ title }}
    <a href="javascript:;" data-toggle="dropdown">
      <i class="fa fa-filter" :class="{ 'text-muted': !keyword }"></i>
    </a>
    <ul class="dropdown-menu" style="padding: 3px">
      <!-- <div class="input-group input-group-sm"> -->
      <v-select @change="search" :options="items" :disabled="items.length == 0"></v-select>
      <!-- </div> -->
    </ul>
  </div>
</template>
<script>
export default {
  props: ["field", "title", "query"],
  data: () => ({
    items: [1, 2],
    keyword: ""
  }),
  mounted() {
    // $(this.$el).on("shown.bs.dropdown", e => this.$refs.input.focus());
  },
  watch: {
    keyword(kw) {
      // reset immediately if empty
      if (kw === "") this.search();
    }
  },
  methods: {
    fetchData() {},
    search(value) {
      const { query } = this;
      // `$props.query` would be initialized to `{ limit: 10, offset: 0, sort: '', order: '' }` by default
      // custom query conditions must be set to observable by using `Vue.set / $vm.$set`
      this.keyword = value;
      this.$set(query, this.field, value);
      query.offset = 0; // reset pagination
    }
  }
};
</script>
<style>
</style>
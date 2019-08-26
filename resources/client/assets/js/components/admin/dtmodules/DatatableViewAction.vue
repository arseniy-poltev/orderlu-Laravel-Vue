<template>
  <div class="btn-group btn-group-xs">
    <router-link
      v-if="$can(xprops.permission_prefix + 'view')"
      :to="{ name: xprops.route + '.show', params: { id: row.id } }"
      class="btn btn-danger"
    >Action</router-link>
  </div>
</template>


<script>
export default {
  props: ["row", "xprops"],
  data() {
    return {
      // Code...
    };
  },
  created() {
    // Code...
  },
  methods: {}
};
</script>


<style scoped>
</style>

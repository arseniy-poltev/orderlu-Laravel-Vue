<template>
  <div>
    <img
      class="my-image"
      :src="row.courier_company.logo_url"
      :alt="row.courier_company.logo_url"
      v-if="row.courier_company.logo_url"
    />
    <span>{{row.courier_company.name}}</span>
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
  }
};
</script>
<style>
.my-image {
  width: 80px;
  /* height: 60px; */
  border-radius: 6px;
}
</style>

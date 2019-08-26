<template>
  <div>
    <country-flag
      :country="row.country_code"
      size="big"
      class="flag-icon"
      style="margin-top:12px"
      v-if="row.country_code!=undefined&&row.country_code != null"
    />
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
.table > tbody > tr > td {
  padding: 4px;
  vertical-align: middle;
  /* vertical-align: inherit; */
  line-height: 1.42857143;
}
</style>

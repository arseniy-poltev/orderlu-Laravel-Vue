<template>
  <div>
    <country-flag
      :country="row.printer_company.country_code"
      size="big"
      class="flag-icon"
      style="margin-top:12px"
      v-if="row.printer_company.country_code!=undefined&&row.printer_company.country_code != null"
    />
    <country-flag
      :country="row.country_code"
      size="big"
      class="flag-icon"
      style="margin-top:12px;margin-left:10px"
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
</style>

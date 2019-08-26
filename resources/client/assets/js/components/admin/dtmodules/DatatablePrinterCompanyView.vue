<template>
  <div v-if="row.printer_company">
    <span style="color:blue">{{row.printer_company.name}}</span>
    <img class="my-image" :src="row.printer_company.logo_url" v-if="row.printer_company.logo_url" />
    <div>
      <div>
        <i class="fas fa-map-marker-alt" style="font-size:16px"></i>
        <br />
        <span style="color:black;font-weight:700">{{row.printer_company.full_address}}</span>
      </div>
      <!-- <country-flag
        :country="row.printer_company.country_code"
        size="normal"
        class="flag-icon cutomize"
        v-if="row.printer_company.country_code!=undefined&&row.printer_company.country_code != null"
      />-->
    </div>
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
  max-width: 60px;
  /* height: 60px; */
  border-radius: 6px;
}
.printer-item {
  /* display: inline-block; */
  margin-left: 10px;
}
.cutomize {
  margin-top: -18px !important;
}
</style>

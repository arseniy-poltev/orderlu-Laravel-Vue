<template>
  <div style="padding-top:5px">
    <div>
      <i class="fas fa-map-marker-alt" style="font-size:16px"></i>
      <br />
      <div style="font-weight:800">
        <span>{{row.continent}}</span>
        <br />
        <span>{{row.country}}</span>
        <p>{{row.region}}</p>
      </div>
    </div>
    <!-- <country-flag
      :country="row.country_code"
      size="normal"
      class="flag-icon cutomize"
      v-if="row.country_code!=undefined&&row.country_code != null"
    />-->
    <!-- <i class="fas fa-map-marker-alt" style="font-size:20px"></i> -->
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
.cutomize {
  margin-top: -18px !important;
}
</style>

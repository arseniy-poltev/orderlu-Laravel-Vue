<template>
  <div v-if="row.courier_company">
    <span style="color:blue">{{row.courier_company.name}}</span>
    <br />
    <img class="my-image" :src="row.courier_company.logo_url" v-if="row.courier_company.logo_url" />
    <!-- <div>
      <i class="fas fa-map-marker-alt" style="font-size:16px"></i>
      <p style="color:black;font-weight:700">{{row.printer_company.full_address}}</p>
    </div>-->
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
</style>

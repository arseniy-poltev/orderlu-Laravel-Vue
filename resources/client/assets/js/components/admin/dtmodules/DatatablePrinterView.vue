<template>
  <div>
    <div v-for="(item,index) in row.printer_companies" v-bind:key="index" class="courier-item">
      <div>
        <span style="color:blue">{{item.name}}</span>
        <span style="color:red;font-size:18px;font-weight:800">{{item.percent}}%</span>
      </div>
      <img class="my-image" :src="item.logo_url" v-if="item.logo_url" />
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
  max-width: 80px;
  /* height: 60px; */
  border-radius: 6px;
}
.courier-item {
  display: inline-block;
  margin-left: 10px;
}
</style>

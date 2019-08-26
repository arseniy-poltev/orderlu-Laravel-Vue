<template>
  <div>
    <img class="my-image" :src="row.logo_url" v-if="row.logo_url" />
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
  /* width: 60px; */
  max-width: 64px;
  /* height: 60px; */
  border-radius: 6px;
}
</style>

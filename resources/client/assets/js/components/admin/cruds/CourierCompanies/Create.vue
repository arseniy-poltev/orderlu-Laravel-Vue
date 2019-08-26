<template>
  <section class="content-wrapper" style="min-height: 960px;">
    <section class="content-header">
      <h1>Courier company</h1>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <form @submit.prevent="submitForm" novalidate>
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">Create</h3>
              </div>

              <div class="box-body">
                <!-- <back-buttton></back-buttton> -->
                <router-link
                  v-if="$can('courier_company_access')"
                  :to="{ name: 'courier_companies.index'}"
                  class="btn btn-default btn-sm"
                >
                  <span class="glyphicon glyphicon-chevron-left"></span> Back to all Courier Companies
                </router-link>
              </div>

              <bootstrap-alert />

              <div class="box-body">
                <div class="form-group">
                  <label for="name">Name *</label>
                  <input
                    type="text"
                    class="form-control"
                    name="name"
                    placeholder="Enter Name *"
                    :value="item.name"
                    @input="updateName"
                    required
                  />
                </div>
                <div class="form-group">
                  <label for="postmen_id">Postmen ID *</label>
                  <input
                    type="text"
                    class="form-control"
                    name="postmen_id"
                    placeholder="Enter Postmen ID *"
                    :value="item.postmen_id"
                    @input="updatePostmen_id"
                    required
                  />
                </div>
                <div class="form-group">
                  <label for="description">Logo of Courier Company</label>
                  <image-file-input ref="imgView" v-bind:image.sync="image" :initial="null"></image-file-input>
                </div>
              </div>

              <div class="box-footer">
                <vue-button-spinner
                  class="btn btn-primary btn-sm"
                  :isLoading="loading"
                  :disabled="loading"
                >Save</vue-button-spinner>
              </div>
            </div>
          </form>
        </div>
      </div>
    </section>
  </section>
</template>


<script>
import { mapGetters, mapActions } from "vuex";

export default {
  data() {
    return {
      // Code...
      image: null
    };
  },
  computed: {
    ...mapGetters("CourierCompaniesSingle", ["item", "loading"])
  },
  created() {
    // Code ...
    this.init();
  },
  destroyed() {
    this.resetState();
  },
  methods: {
    ...mapActions("CourierCompaniesSingle", [
      "storeData",
      "resetState",
      "setName",
      "setPostmen_id",
      "setLogo"
    ]),
    init() {
      this.image = null;
      this.resetState();
    },
    updateName(e) {
      this.setName(e.target.value);
    },
    updatePostmen_id(e) {
      this.setPostmen_id(e.target.value);
    },
    submitForm() {
      this.setLogo(this.image);
      this.storeData()
        .then(() => {
          this.$router.push({ name: "courier_companies.index" });
          this.$eventHub.$emit("create-success");
          this.$refs.imgView.resetImage();
        })
        .catch(error => {
          console.error(error);
        });
    }
  }
};
</script>


<style scoped>
</style>

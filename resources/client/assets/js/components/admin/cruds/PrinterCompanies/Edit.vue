<template>
  <section class="content-wrapper" style="min-height: 960px;">
    <section class="content-header">
      <h1>Printer company</h1>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <form @submit.prevent="submitForm" novalidate>
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">Edit</h3>
              </div>

              <div class="box-body">
                <!-- <back-buttton></back-buttton> -->
                <router-link
                  v-if="$can('printer_company_access')"
                  :to="{ name: 'printer_companies.index'}"
                  class="btn btn-default btn-sm"
                >
                  <span class="glyphicon glyphicon-chevron-left"></span> Back to all Printer Companies
                </router-link>
              </div>
              <div class="row" v-if="loading">
                <div class="col-xs-4 col-xs-offset-4">
                  <div class="alert text-center">
                    <i class="fa fa-spin fa-refresh"></i> Loading
                  </div>
                </div>
              </div>

              <bootstrap-alert />

              <div class="box-body" v-if="!loading">
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
                  <label for="postmen_id">Full Address *</label>
                  <input
                    type="text"
                    class="form-control"
                    name="full_address"
                    placeholder="Enter Full Address *"
                    :value="item.full_address"
                    @input="updateFullAddress"
                    required
                  />
                </div>
                <div class="form-group">
                  <label for="title">Country *</label>
                  <input
                    type="text"
                    class="form-control"
                    name="country"
                    placeholder="Enter Country *"
                    :value="item.country"
                    @input="updateCountry"
                    required
                  />
                </div>
                <div class="form-group">
                  <label for="title">State *</label>
                  <input
                    type="text"
                    class="form-control"
                    name="state"
                    placeholder="Enter State *"
                    :value="item.state"
                    @input="updateState"
                    required
                  />
                </div>
                <div class="form-group">
                  <label for="title">City *</label>
                  <input
                    type="text"
                    class="form-control"
                    name="city"
                    placeholder="Enter City *"
                    :value="item.city"
                    @input="updateCity"
                    required
                  />
                </div>
                <div class="form-group">
                  <label for="title">Zip Code *</label>
                  <input
                    type="text"
                    class="form-control"
                    name="zip_code"
                    placeholder="Enter Zip Code *"
                    :value="item.zip_code"
                    @input="updateZipCode"
                    required
                  />
                </div>
                <div class="form-group">
                  <label for="title">Street Address *</label>
                  <input
                    type="text"
                    class="form-control"
                    name="street_address"
                    placeholder="Enter Street Address *"
                    :value="item.street_address"
                    @input="updateStreetAddress"
                    required
                  />
                </div>
                <div class="form-group">
                  <label for="description">Logo of Printer Company</label>
                  <image-file-input
                    ref="imgView"
                    v-bind:image.sync="image"
                    :initial="item.logo_url"
                  ></image-file-input>
                </div>
                <div class="form-group">
                  <label for="users">Users *</label>
                  <v-select
                    name="users"
                    label="name"
                    @input="updateUsers"
                    :value="item.users"
                    :options="usersAll"
                    :disabled="usersAll.length == 0"
                    multiple
                  />
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
    ...mapGetters("PrinterCompaniesSingle", ["item", "loading", "usersAll"])
  },
  created() {
    this.fetchData(this.$route.params.id);
  },
  destroyed() {
    this.resetState();
  },
  watch: {
    "$route.params.id": function() {
      this.resetState();
      this.fetchData(this.$route.params.id);
    }
  },
  methods: {
    ...mapActions("PrinterCompaniesSingle", [
      "fetchData",
      "updateData",
      "resetState",
      "setName",
      "setFullAddress",
      "setCountry",
      "setState",
      "setCity",
      "setZipCode",
      "setStreetAddress",
      "setLogo",
      "setUsers"
    ]),
    updateName(e) {
      this.setName(e.target.value);
    },
    updateFullAddress(e) {
      this.setFullAddress(e.target.value);
    },
    updateCountry(e) {
      this.setCountry(e.target.value);
    },
    updateState(e) {
      this.setState(e.target.value);
    },
    updateCity(e) {
      this.setCity(e.target.value);
    },
    updateZipCode(e) {
      this.setZipCode(e.target.value);
    },
    updateStreetAddress(e) {
      this.setStreetAddress(e.target.value);
    },
    updateUsers(value) {
      this.setUsers(value);
    },

    submitForm() {
      this.setLogo(this.image);
      this.updateData()
        .then(() => {
          this.$router.push({ name: "printer_companies.index" });
          this.$eventHub.$emit("update-success");
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

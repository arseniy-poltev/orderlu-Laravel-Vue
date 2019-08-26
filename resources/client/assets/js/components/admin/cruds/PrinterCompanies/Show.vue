<template>
  <section class="content-wrapper" style="min-height: 960px;">
    <section class="content-header">
      <h1>Printer company</h1>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">View</h3>
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

            <div class="box-body">
              <div class="row" v-if="loading">
                <div class="col-xs-4 col-xs-offset-4">
                  <div class="alert text-center">
                    <i class="fa fa-spin fa-refresh"></i> Loading
                  </div>
                </div>
              </div>
              <div class="row" v-if="!loading">
                <div class="col-xs-6">
                  <table class="table table-bordered table-striped">
                    <tbody>
                      <tr>
                        <th>#</th>
                        <td>{{ item.id }}</td>
                      </tr>
                      <tr>
                        <th>Name</th>
                        <td>{{ item.name }}</td>
                      </tr>
                      <tr>
                        <th>Full Address</th>
                        <td>{{ item.full_address }}</td>
                      </tr>
                      <tr>
                        <th>Country</th>
                        <td>{{ item.country }}</td>
                      </tr>
                      <tr>
                        <th>State</th>
                        <td>{{ item.state }}</td>
                      </tr>
                      <tr>
                        <th>City</th>
                        <td>{{ item.city }}</td>
                      </tr>
                      <tr>
                        <th>Zip Code</th>
                        <td>{{ item.zip_code }}</td>
                      </tr>
                      <tr>
                        <th>Street Address</th>
                        <td>{{ item.street_address }}</td>
                      </tr>
                      <tr>
                        <th>Logo Image</th>
                        <td>
                          <img :src="item.logo_url" height="60" width="60" class="my-image" />
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
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
    };
  },
  created() {
    this.fetchData(this.$route.params.id);
  },
  destroyed() {
    this.resetState();
  },
  computed: {
    ...mapGetters("PrinterCompaniesSingle", ["item", "loading"])
  },
  watch: {
    "$route.params.id": function() {
      this.resetState();
      this.fetchData(this.$route.params.id);
    }
  },
  methods: {
    ...mapActions("PrinterCompaniesSingle", ["fetchData", "resetState"])
  }
};
</script>


<style scoped>
.my-image {
  /* border: #f0a0a0;
  border-style: dotted; */
  border-radius: 6px;
}
</style>

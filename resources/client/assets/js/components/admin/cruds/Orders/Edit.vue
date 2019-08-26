<template>
  <section class="content-wrapper" style="min-height: 960px;">
    <section class="content-header">
      <h1>Order</h1>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <form @submit.prevent="submitForm">
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">Edit</h3>
              </div>

              <div class="box-body">
                <!-- <back-buttton></back-buttton> -->
                <router-link
                  v-if="$can('order_access')"
                  :to="{ name: 'orders.index'}"
                  class="btn btn-default btn-sm"
                >
                  <span class="glyphicon glyphicon-chevron-left"></span> Back to all Orders
                </router-link>
                <button
                  type="button"
                  class="btn btn-default btn-sm"
                  @click="fetchData($route.params.id)"
                >
                  <i class="fa fa-refresh" :class="{'fa-spin': loading}"></i> Refresh
                </button>
              </div>

              <bootstrap-alert />
              <div class="row" v-if="loading">
                <div class="col-xs-4 col-xs-offset-4">
                  <div class="alert text-center">
                    <i class="fa fa-spin fa-refresh"></i> Loading
                  </div>
                </div>
              </div>
              <div class="box-body" v-if="!loading">
                <div class="form-group">
                  <label for="title">Order ID *</label>
                  <p style="font-size:24px">{{item.order_id}}</p>
                </div>
                <div class="form-group">
                  <label for="title">Status</label>
                  <DatatableStatusRow :temp="item.status"></DatatableStatusRow>
                </div>
                <div class="form-group">
                  <label for="title">Number of Books</label>
                  <p style="font-size:24px">{{item.book_count}}</p>
                </div>
                <div class="form-group">
                  <label for="region">Select Printer Company *</label>
                  <br />
                  <img
                    class="my-image"
                    v-if="item.printer_company"
                    :src="item.printer_company.logo_url"
                  />
                  <v-select
                    :options="printer_companies"
                    :clearable="false"
                    @change="updatePrinterCompany"
                    label="name"
                    :disabled="printer_companies.length == 0 || item.status != 'pending'"
                    :value="item.printer_company"
                  >
                    <template slot="option" slot-scope="printer_company">
                      <img
                        class="my-image"
                        :src="printer_company.logo_url"
                        v-if="printer_company.logo_url"
                      />
                      <span class="country-label">{{ printer_company.name }}</span>
                    </template>
                  </v-select>
                </div>
                <div class="form-group">
                  <label for="region">Select Courier Company *</label>
                  <br />
                  <img
                    v-if="item.courier_company"
                    class="my-image"
                    :src="item.courier_company.logo_url"
                  />
                  <v-select
                    :options="courier_companies"
                    :clearable="false"
                    @change="updateCourierCompany"
                    label="name"
                    :disabled="courier_companies.length == 0 || item.status == 'finished'"
                    :value="item.courier_company"
                  >
                    <template slot="option" slot-scope="courier_company">
                      <img
                        class="my-image"
                        :src="courier_company.logo_url"
                        v-if="courier_company.logo_url"
                      />
                      <span class="country-label">{{ courier_company.name }}</span>
                    </template>
                  </v-select>
                </div>
                <div class="form-group">
                  <label for="title">Ordered At</label>
                  <p style="font-size:24px">{{item.created_at}}</p>
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
import DatatableStatusRow from "../../dtmodules/DatatableStatusRow";
export default {
  data() {
    return {
      // Code...
    };
  },
  components: {
    DatatableStatusRow
  },
  computed: {
    ...mapGetters("OrdersSingle", [
      "item",
      "loading",
      "printer_companies",
      "courier_companies"
    ])
  },
  created() {
    this.init();
  },
  destroyed() {
    this.resetState();
  },
  watch: {
    "$route.params.id": function() {
      this.init();
    }
  },
  methods: {
    ...mapActions("OrdersSingle", [
      "resetState",
      "fetchData",
      "updateData",
      "setBookName",
      "setBookCount",
      "setPrinterCompany",
      "setCourierCompany",
      "fetchPrinterCompany",
      "fetchCourierCompany"
    ]),
    init() {
      this.resetState();
      this.fetchPrinterCompany();
      this.fetchCourierCompany();
      this.fetchData(this.$route.params.id);
    },
    updateBookName(e) {
      this.setBookName(e.target.value);
    },
    updateBookCount(e) {
      this.setBookCount(e.target.value);
    },
    updatePrinterCompany(value) {
      this.setPrinterCompany(value);
    },
    updateCourierCompany(value) {
      this.setCourierCompany(value);
    },
    submitForm() {
      this.updateData()
        .then(() => {
          this.$router.push({
            name: "orders.index"
          });
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
.my-image {
  width: 50px;
  /* height: 50px; */
  display: inline-block;
  border-radius: 4px;
  margin-bottom: 2px;
}
</style>

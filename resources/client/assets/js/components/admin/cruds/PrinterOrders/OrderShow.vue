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
                <back-buttton></back-buttton>
                <!-- <router-link :to="{ name: 'orders.index'}" class="btn btn-default btn-sm">
                  <span class="glyphicon glyphicon-chevron-left"></span> Back to all Orders
                </router-link>-->
                <button
                  type="button"
                  class="btn btn-default btn-sm"
                  @click="fetchOrderData($route.params.id)"
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
                  <label for="title">Number of Books</label>
                  <div v-if="item.courier_company">
                    <span style="color:blue">{{item.courier_company.name}}</span>
                    <br />
                    <img
                      class="my-image"
                      :src="item.courier_company.logo_url"
                      v-if="item.courier_company.logo_url"
                    />
                  </div>
                </div>

                <div class="form-group">
                  <label for="title">Ordered At</label>
                  <p style="font-size:24px">{{item.created_at}}</p>
                </div>
              </div>

              <div class="box-footer">
                <vue-button-spinner
                  class="btn btn-success btn-sm"
                  :isLoading="loading"
                  :disabled="loading"
                >Make Label!</vue-button-spinner>
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
    ...mapGetters("PrinterOrdersSingle", ["item", "loading"])
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
    ...mapActions("PrinterOrdersSingle", ["resetState", "fetchOrderData"]),
    init() {
      this.resetState();
      this.fetchOrderData(this.$route.params.id);
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

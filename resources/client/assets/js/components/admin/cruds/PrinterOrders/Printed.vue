<template>
  <section class="content-wrapper" style="min-height: 960px;">
    <section class="content-header">
      <h1>Orders Printed</h1>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">List</h3>
            </div>

            <div class="box-body">
              <div class="btn-group">
                <router-link
                  v-if="$can(xprops.permission_prefix + 'create')"
                  :to="{ name: xprops.route + '.create' }"
                  class="btn btn-success btn-sm"
                >
                  <i class="fa fa-plus"></i> Add new
                </router-link>

                <button type="button" class="btn btn-default btn-sm" @click="getData">
                  <i class="fa fa-refresh" :class="{'fa-spin': loading}"></i> Refresh
                </button>
              </div>
            </div>

            <div class="box-body">
              <div class="row">
                <div class="col-md-3 form-group">
                  <label for="title">Scan Code</label>
                  <input
                    type="text"
                    class="form-control"
                    name="search"
                    placeholder="Enter Order ID"
                    required
                    v-model="keyword"
                  />
                </div>
              </div>
              <div class="row" v-if="loading">
                <div class="col-xs-4 col-xs-offset-4">
                  <div class="alert text-center">
                    <i class="fa fa-spin fa-refresh"></i> Loading
                  </div>
                </div>
              </div>

              <datatable
                v-if="!loading"
                :columns="columns"
                :data="data"
                :total="total"
                :query="query"
                :xprops="xprops"
                :pageSizeOptions="pageSizeOptions"
              />
            </div>
          </div>
        </div>
      </div>
    </section>
  </section>
</template>


<script>
import { mapGetters, mapActions } from "vuex";
import DatatableViewAction from "../../dtmodules/DatatableViewAction";
import DatatablePrinterCompanyView from "../../dtmodules/DatatablePrinterCompanyView";
import DatatableCourierCompanyView from "../../dtmodules/DatatableCourierCompanyView";
import DatatableFilter from "../../dtmodules/DatatableFilter";
import DatatableSelectFilter from "../../dtmodules/DatatableSelectFilter";
import DatatableFlagView from "../../dtmodules/DatatableFlagView";
import DatatableStatusRow from "../../dtmodules/DatatableStatusRow";

export default {
  data() {
    return {
      pageSizeOptions: [5, 10, 15, 20],
      columns: [
        // { title: "#", field: "id", sortable: true, colStyle: "width: 50px;" },
        {
          title: "Order ID",
          field: "order_id",
          sortable: true,
          colStyle: "width: 100px;"
        },

        {
          title: "Country",
          field: "country",
          thComp: DatatableFilter,
          tdComp: DatatableFlagView,
          sortable: true
        },

        {
          title: "Status",
          field: "status",
          sortable: true,
          tdComp: DatatableStatusRow
        },
        { title: "Ordered At", field: "created_at", sortable: true },
        // { title: "Assigned At", field: "assigned_at", sortable: true },
        {
          title: "Courier Company",
          field: "courier_company",
          tdComp: DatatableCourierCompanyView,
          thComp: DatatableFilter
          // sortable: true
        },
        {
          title: "Book Count",
          field: "book_count",
          sortable: true
        },
        {
          title: "Actions",
          tdComp: DatatableViewAction,
          visible: true,
          thClass: "text-right",
          tdClass: "text-right",
          colStyle: "width: 130px;"
        }
      ],
      keyword: "",
      query: { sort: "id", order: "desc", limit: 5 },
      xprops: {
        module: "PrinterOrdersIndex",
        route: "printer_orders",
        permission_prefix: "order_"
      }
    };
  },
  created() {},
  mounted() {
    this.$root.relationships = this.relationships;
    this.getData();
  },
  destroyed() {
    this.resetState();
  },
  computed: {
    ...mapGetters("PrinterOrdersIndex", [
      "data",
      "total",
      "loading",
      "relationships"
    ])
  },
  watch: {
    query: {
      handler(query) {
        console.log(query);
        this.setQuery(query);
      },
      deep: true
    },
    keyword: {
      handler(kw) {
        console.log(kw);
        this.search();
      },
      deep: true
    }
  },
  methods: {
    ...mapActions("PrinterOrdersIndex", [
      "fetchPrintedOrder",
      "setQuery",
      "resetState"
    ]),
    getData() {
      this.query = { sort: "id", order: "desc", limit: 5 };
      this.keyword = "";
      this.fetchPrintedOrder();
    },
    search() {
      // `$props.query` would be initialized to `{ limit: 10, offset: 0, sort: '', order: '' }` by default
      // custom query conditions must be set to observable by using `Vue.set / $vm.$set`
      this.$set(this.query, "scan_code", this.keyword);
      this.query.offset = 0; // reset pagination
      this.setQuery(this.query);
    }
  }
};
</script>


<style scoped>
</style>

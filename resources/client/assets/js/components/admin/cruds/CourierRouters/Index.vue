<template>
  <section class="content-wrapper" style="min-height: 960px;">
    <section class="content-header">
      <h1>Courier Company Routers</h1>
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

                <button type="button" class="btn btn-default btn-sm" @click="fetchData">
                  <i class="fa fa-refresh" :class="{'fa-spin': loading}"></i> Refresh
                </button>
              </div>
            </div>

            <div class="box-body">
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
import DatatableActions from "../../dtmodules/DatatableActions";
import DatatableCourierView from "../../dtmodules/DatatableCourierView";
import DatatablePrinterCompanyView from "../../dtmodules/DatatablePrinterCompanyView";
import DatatableLocationView from "../../dtmodules/DatatableLocationView";
// import DatatableFlagView from "../../dtmodules/DatatableFlagView";
import DatatableFromToView from "../../dtmodules/DatatableFromToView";

export default {
  data() {
    return {
      columns: [
        { title: "#", field: "id", sortable: true, colStyle: "width: 50px;" },
        {
          title: "From-To",
          field: "country",
          sortable: true,
          tdComp: DatatableFromToView,
          colStyle: "width: 10%;"
        },
        {
          title: "Origin(From)",
          field: "printer_company",
          sortable: true,
          tdComp: DatatablePrinterCompanyView,
          colStyle: "width: 15%;"
        },
        {
          title: "Destination(To)",
          field: "continent",
          // sortable: true,
          tdComp: DatatableLocationView,
          colStyle: "width: 10%;"
        },

        // {
        //   title: "Country",
        //   field: "country",
        //   sortable: true,
        //   colStyle: "width: 15%;"
        // },
        // {
        //   title: "Region",
        //   field: "region",
        //   sortable: true,
        //   colStyle: "width: 15%;"
        // },
        {
          title: "Courier Company",
          tdComp: DatatableCourierView,
          colStyle: "width: 35%;"
        },
        { title: "Updated", field: "updated_at", sortable: true },

        {
          title: "Actions",
          tdComp: DatatableActions,
          visible: true,
          thClass: "text-right",
          tdClass: "text-right",
          colStyle: "width: 130px;"
        }
      ],
      query: { sort: "updated_at", order: "desc" },
      xprops: {
        module: "CourierRoutersIndex",
        route: "courier_routers",
        permission_prefix: "courier_router_"
      }
    };
  },
  created() {
    this.$root.relationships = this.relationships;
    this.fetchData();
  },
  destroyed() {
    this.resetState();
  },
  computed: {
    ...mapGetters("CourierRoutersIndex", [
      "data",
      "total",
      "loading",
      "relationships"
    ])
  },
  watch: {
    query: {
      handler(query) {
        this.setQuery(query);
      },
      deep: true
    }
  },
  methods: {
    ...mapActions("CourierRoutersIndex", [
      "fetchData",
      "setQuery",
      "resetState"
    ])
  }
};
</script>


<style scoped>
</style>

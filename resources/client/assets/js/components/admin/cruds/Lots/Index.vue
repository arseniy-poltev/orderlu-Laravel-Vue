<template>
  <section class="content-wrapper" style="min-height: 960px;">
    <section class="content-header">
      <h1>Lots/Queue</h1>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <!-- <h3 class="box-title">List of Lots</h3> -->
              <span style="font-size:25px">Nubmer of Books pending to Print:</span>
              <span
                v-if="!loadingCnt"
                style="font-size:25px;color:blue;font-weight:900"
              >{{pendingCnt}}</span>
              <i v-if="loadingCnt" class="fa fa-refresh" :class="{'fa-spin': loadingCnt}"></i>
              <br />
              <button type="button" class="btn btn-default btn-sm" @click="fetchPendingBookCnt">
                <i class="fa fa-refresh" :class="{'fa-spin': loadingCnt}"></i> Refresh
              </button>
            </div>

            <div class="box-body">
              <div class="row"></div>
            </div>

            <div class="box-body">
              <div class="btn-group">
                <button
                  type="button"
                  :disabled="showFlag"
                  class="btn btn-success btn-sm"
                  @click="showInputField"
                >
                  <i class="fa fa-plus"></i> Assign a new Lot to Printer
                </button>
                <button type="button" class="btn btn-default btn-sm" @click="fetchData">
                  <i class="fa fa-refresh" :class="{'fa-spin': loading}"></i> Refresh
                </button>
              </div>
              <div class="row col-md-12" v-if="showFlag">
                <div class="col-md-3 col-sm-4 col-xs-6 input-field">
                  <i
                    v-if="assigning"
                    style="margin-left:10px"
                    class="fa fa-refresh"
                    :class="{'fa-spin': assigning}"
                  ></i>
                  <div v-if="!assigning">
                    <input
                      type="number"
                      min="1"
                      max="100"
                      class="form-control"
                      name="number_books"
                      placeholder="Enter Number of Books to assign"
                      v-model="numberBooks"
                      required
                    />
                    <button
                      type="button"
                      class="btn btn-default btn-sm btn-assign"
                      @click="cancel"
                    >Cancel</button>
                    <button
                      type="button"
                      class="btn btn-danger btn-sm btn-assign"
                      @click="assignNewLot"
                    >
                      <i class="fas fa-plus"></i> Assign
                    </button>
                  </div>
                </div>
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

export default {
  data() {
    return {
      columns: [
        {
          title: "Number of LOT",
          field: "lot_number",
          sortable: true
          //   colStyle: "width: 100px;"
        },
        {
          title: "Number of Books",
          field: "number_books"
          //   colStyle: "width: 80px;"
        },
        { title: "Created", field: "created_at", sortable: true },
        {
          title: "Number of Printed",
          field: "number_printed"
          //   colStyle: "width: 80px;"
        },
        { title: "Finished", field: "finished_at", sortable: true }
      ],
      query: { sort: "id", order: "desc" },
      xprops: {
        module: "PrinterLotsIndex",
        route: "lots",
        permission_prefix: "lot_"
      },
      showFlag: false,
      numberBooks: null
    };
  },
  created() {
    this.$root.relationships = this.relationships;
    this.fetchPendingBookCnt();
    this.fetchData();
  },
  destroyed() {
    this.resetState();
  },
  computed: {
    ...mapGetters("PrinterLotsIndex", [
      "data",
      "total",
      "loading",
      "assigning",
      "loadingCnt",
      "pendingCnt",
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
    ...mapActions("PrinterLotsIndex", [
      "fetchData",
      "fetchPendingBookCnt",
      "setQuery",
      "resetState",
      "createNewLot"
    ]),
    showInputField() {
      this.showFlag = true;
      this.numberBooks = this.pendingCnt;
    },
    assignNewLot() {
      if (this.numberBooks == 0) {
        alert("Number of Books cannot be 0!");
        return;
      }
      if (this.numberBooks > this.pendingCnt || this.numberBooks < 1) {
        alert("Number of Books must be [1," + this.pendingCnt + "]");
        return;
      }
      this.createNewLot(this.numberBooks);
    },
    cancel() {
      this.showFlag = false;
    }
  }
};
</script>


<style scoped>
.input-field {
  border-style: dashed;
  border-color: brown;
  margin-top: 5px;
  padding: 5px;
}
.btn-assign {
  margin-top: 5px;
  margin-left: 3px;
  float: right;
}
</style>


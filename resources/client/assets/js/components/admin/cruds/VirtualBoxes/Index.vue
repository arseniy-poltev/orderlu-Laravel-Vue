<template>
  <section class="content-wrapper" style="min-height: 960px;">
    <section class="content-header">
      <h1>Virtual Boxes</h1>
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
                <!-- <back-buttton></back-buttton> -->

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
                    placeholder="Enter Order ID or Book ID"
                    required
                    v-model="scanCode"
                  />
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
            </div>

            <div class="box-body" v-if="!loading">
              <div class="col-md-3" v-for="(box,index) in data" v-bind:key="index">
                <div
                  class="box direct-chat direct-chat-success box-solid"
                  v-bind:class="getBoxClass(box)"
                  :id="'box' + box.id"
                >
                  <div class="box-header with-border">
                    <h2 class="box-title virtual-box-title">{{box.number}}</h2>

                    <div class="box-tools pull-right">
                      <span style="font-size:16px">{{box.order_id}}</span>
                      <span
                        data-toggle="tooltip"
                        title
                        class="badge bg-red count-show"
                        :data-original-title="(box.total - box.count) + ' books are left!'"
                      >{{box.count}}/{{box.total}}</span>
                      <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                      </button>
                      <!-- <button
                        type="button"
                        class="btn btn-box-tool"
                        data-toggle="tooltip"
                        title
                        data-widget="chat-pane-toggle"
                        data-original-title="Contacts"
                      >
                        <i class="fa fa-comments"></i>
                      </button>-->
                    </div>
                  </div>

                  <div class="box-body" style>
                    <div class="direct-chat-messages">
                      <div class="box-group">
                        <div
                          class="panel box box-primary"
                          v-for="(book,index) in box.books"
                          v-bind:key="index"
                          :id="book.id"
                        >
                          <div class="box-header with-border">
                            <h4 class="box-title">
                              <a
                                data-toggle="collapse"
                                data-parent="#accordion"
                                :href="'#collapse' + book.id"
                                aria-expanded="false"
                                class="collapsed"
                              >
                                <i class="fas fa-book margin-right-10"></i>
                                <span>{{getBookID(book.id)}}</span>
                              </a>
                            </h4>
                          </div>
                          <div
                            :id="'collapse' + book.id"
                            class="panel-collapse collapse"
                            aria-expanded="false"
                            style="height: 0px;"
                          >
                            <div class="box-body">
                              <table class="table table-bordered table-striped">
                                <tbody>
                                  <tr>
                                    <th style="width:40%">#</th>
                                    <td>{{getBookID(book.id)}}</td>
                                  </tr>
                                  <tr>
                                    <th>Name Of Book</th>
                                    <td>{{ book.book_name }}</td>
                                  </tr>
                                  <tr>
                                    <th>Order At</th>
                                    <td>{{ book.created_at }}</td>
                                  </tr>
                                  <tr>
                                    <th>Assigned At</th>
                                    <td>{{ book.assigned_at }}</td>
                                  </tr>
                                  <tr>
                                    <th>Printed At</th>
                                    <td>{{ book.printed_at }}</td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- /.box-body -->
                  <div class="box-footer" style>
                    <button
                      type="button"
                      class="btn btn-block btn-success btn-sm"
                      :disabled="box.total != box.count || box.processing || !box.order_id"
                      @click="publish(box.id)"
                    >
                      <i v-if="box.processing" class="fa fa-refresh fa-spin margin-right-10"></i>
                      <span>Publish</span>
                    </button>
                  </div>
                  <!-- /.box-footer-->
                </div>
                <!--/.direct-chat -->
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
      query: { sort: "updated_at", order: "desc" },
      scanCode: ""
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
    ...mapGetters("VirtualBoxesIndex", [
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
    scanCode: {
      handler(sc) {
        console.log(sc);
        this.search();
      },
      deep: true
    }
  },
  methods: {
    ...mapActions("VirtualBoxesIndex", [
      "fetchData",
      "setQuery",
      "resetState",
      "publishOrder"
    ]),
    getBookID(id) {
      var idStr = id + "";
      return "BOOK-" + idStr.padStart(10, "0");
    },
    getBoxClass(box) {
      if (box.total == 0) {
        return "box-default";
      }
      return box.order_id && box.total == box.count
        ? "box-danger"
        : "box-success";
    },
    getData() {
      this.query = { sort: "updated_at", order: "desc" };
      this.scanCode = "";
      this.fetchData();
    },
    publish(id) {
      this.publishOrder(id)
        .then(() => {})
        .catch(error => {
          console.error(error);
          alert(error);
        });
    },
    search() {
      // `$props.query` would be initialized to `{ limit: 10, offset: 0, sort: '', order: '' }` by default
      // custom query conditions must be set to observable by using `Vue.set / $vm.$set`
      this.$set(this.query, "scan_code", this.scanCode);
      //this.query.offset = 0; // reset pagination
      this.setQuery(this.query);
    }
  }
};
</script>
<style slot-scope>
.book-item {
  background: #00c0ef;
  border-radius: 10px;
  color: white;
  font-size: 20px;
  font-weight: 800;
  padding: 10px;
  margin: 1px;
}
.direct-chat-messages {
  /* display: flex; */
  /* justify-content: flex-end; */
  /* flex-direction: column; */
  /* overflow-y: scroll; */
  /* max-height: 500px; */
  /* display: table-cell; */
  vertical-align: bottom;
}
.virtual-box-title {
  text-transform: uppercase;
  margin-bottom: 2px;
}
.count-show {
  font-size: 24px;
}
</style>



<template>
  <section class="content-wrapper" style="min-height: 960px;">
    <section class="content-header">
      <h1>Book</h1>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">View</h3>
            </div>

            <div class="box-body">
              <back-buttton></back-buttton>
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
                        <th style="width:40%">Order ID</th>
                        <td>{{ item.order_id }}</td>
                      </tr>
                      <tr>
                        <th>Book ID</th>
                        <td>{{ item.book_id }}</td>
                      </tr>
                      <tr>
                        <th>Name Of Book</th>
                        <td>
                          <h3>{{ item.book_name }}</h3>
                        </td>
                      </tr>
                      <tr>
                        <th>Language</th>
                        <td>{{ item.language }}</td>
                      </tr>
                      <tr>
                        <th>Web PDF</th>
                        <td>
                          <a :href="item.pdf_url">
                            <i class="fas fa-file-pdf margin-right-10"></i>
                            PDF
                          </a>
                        </td>
                      </tr>
                      <tr>
                        <th>Print PDF</th>
                        <td>
                          <a :href="item.pdf_url">
                            <i class="fas fa-file-pdf margin-right-10"></i>
                            PDF
                          </a>
                        </td>
                      </tr>

                      <tr>
                        <th>Status</th>
                        <td>
                          <DatatableStatusRow :temp="item.status"></DatatableStatusRow>
                        </td>
                      </tr>
                      <tr>
                        <th>Courier Company</th>
                        <td>
                          <div v-if="item.courier_company">
                            <span style="color:blue">{{item.courier_company.name}}</span>
                            <br />
                            <img
                              class="my-image"
                              :src="item.courier_company.logo_url"
                              v-if="item.courier_company.logo_url"
                            />
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th>Order At</th>
                        <td>{{ item.created_at }}</td>
                      </tr>
                      <tr>
                        <th>Assigned At</th>
                        <td>{{ item.assigned_at }}</td>
                      </tr>
                      <tr>
                        <th>Printed At</th>
                        <td>{{ item.printed_at }}</td>
                      </tr>
                      <tr>
                        <th>Picked At</th>
                        <td>{{ item.picked_at }}</td>
                      </tr>
                      <tr>
                        <th>Delivered At</th>
                        <td>{{ item.delivered_at }}</td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="box-footer">
                    <vue-button-spinner
                      v-if="item.status == 'printing'"
                      class="btn btn-danger"
                      :isLoading="loading"
                      :disabled="loading"
                      :clickFunc="changeStatusToPrinted"
                    >This book is printed!</vue-button-spinner>
                    <vue-button-spinner
                      v-if="item.status == 'checking'"
                      class="btn btn-success"
                      :isLoading="loading"
                      :disabled="loading"
                      :clickFunc="changeStatusToPrinting"
                    >This book is ready to print!</vue-button-spinner>
                    <vue-button-spinner
                      v-if="item.status == 'printed'"
                      class="btn btn-danger"
                      :isLoading="loading"
                      :disabled="loading"
                    >Print Label!</vue-button-spinner>
                  </div>
                </div>
                <div class="col-xs-6">
                  <!-- <MyCustomeButton></MyCustomeButton> -->
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
import DatatableFlagView from "../../dtmodules/DatatableFlagView";
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
  created() {
    this.fetchBookData(this.$route.params.id);
  },
  destroyed() {
    this.resetState();
  },
  computed: {
    ...mapGetters("PrinterOrdersSingle", ["item", "loading"])
  },
  watch: {
    "$route.params.id": function() {
      this.resetState();
      this.fetchBookData(this.$route.params.id);
    }
  },
  methods: {
    ...mapActions("PrinterOrdersSingle", [
      "fetchBookData",
      "resetState",
      "changeStatusAsPrinted",
      "changeStatusAsPrinting"
    ]),
    changeStatusToPrinted() {
      this.changeStatusAsPrinted()
        .then(() => {
          this.$router.push({
            name: "printing_orders.index"
          });
          this.$eventHub.$emit("update-success");
        })
        .catch(error => {
          console.error(error);
        });
    },
    changeStatusToPrinting() {
      this.changeStatusAsPrinting()
        .then(() => {
          this.$router.push({
            name: "checking_orders.index"
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
  max-width: 60px;
  /* height: 60px; */
  border-radius: 6px;
}
</style>

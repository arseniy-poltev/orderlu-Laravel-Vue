<template>
  <section class="content-wrapper" style="min-height: 960px;">
    <section class="content-header">
      <h1>Order</h1>
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
                        <th>#</th>
                        <td>{{ item.id }}</td>
                      </tr>
                      <tr>
                        <th>Name Of Book</th>
                        <td>{{ item.book_name }}</td>
                      </tr>
                      <tr>
                        <th>Count of Book</th>
                        <td>{{ item.book_count }}</td>
                      </tr>
                      <tr>
                        <th>Printer Company</th>
                        <td>{{ item.printer_company }}</td>
                      </tr>
                      <tr>
                        <th>Courier Company</th>
                        <td>{{ item.courier_company }}</td>
                      </tr>
                      <tr>
                        <th>Status</th>
                        <td>
                          <DatatableStatusRow :temp="item.status"></DatatableStatusRow>
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
    this.fetchData(this.$route.params.id);
  },
  destroyed() {
    this.resetState();
  },
  computed: {
    ...mapGetters("OrdersSingle", ["item", "loading"])
  },
  watch: {
    "$route.params.id": function() {
      this.resetState();
      this.fetchData(this.$route.params.id);
    }
  },
  methods: {
    ...mapActions("OrdersSingle", ["fetchData", "resetState"])
  }
};
</script>

<style scoped>
</style>

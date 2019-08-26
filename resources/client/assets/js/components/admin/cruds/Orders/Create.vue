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
                <h3 class="box-title">Create</h3>
              </div>

              <div class="box-body">
                <back-buttton></back-buttton>
              </div>

              <bootstrap-alert />

              <div class="box-body">
                <div class="row col-md-12">
                  <div class="form-group">
                    <div>
                      <span style="font-weight:900">Add Books *</span>
                      <i class="fas fa-plus-square add-btn" @click="addBook"></i>
                    </div>
                    <br />
                    <div
                      class="row col-md-8 col-xs-12 col-sm-12 book-field"
                      v-for="(book,index) in books"
                      v-bind:key="index"
                    >
                      <div>
                        <div class="col-md-1 col-xs-1 col-sm-1">
                          <i
                            v-if="index != 0"
                            class="fas fa-minus-square remove-btn pull-right"
                            @click="removeBook(index)"
                          ></i>
                        </div>
                        <div class="col-md-3 col-xs-3 col-sm-3">
                          <input
                            type="text"
                            name="book_name"
                            class="form-control"
                            placeholder="Enter Book Name"
                            v-model="book.name"
                            required
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <br />
                <!-- <div class="form-group">
                  <label for="title">Name of Book *</label>
                  <input
                    type="text"
                    class="form-control"
                    name="name"
                    placeholder="Enter Book Name *"
                    :value="item.book_name"
                    @input="updateBookName"
                    required
                  />
                </div>
                <div class="form-group">
                  <label for="title">Number of Books *</label>
                  <input
                    type="number"
                    min="1"
                    max="10"
                    default="1"
                    class="form-control"
                    name="count"
                    placeholder="Enter Number of Books *"
                    :value="item.book_count"
                    @input="updateBookCount"
                    required
                  />
                </div>-->
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
                  <label for="title">Apt/Suite Number *</label>
                  <input
                    type="text"
                    class="form-control"
                    name="suite_number"
                    placeholder="Enter Apt/Suite Number *"
                    :value="item.suite_number"
                    @input="updateSuiteNumber"
                    required
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
      books: [
        {
          name: null
        }
      ]
    };
  },
  computed: {
    ...mapGetters("OrdersSingle", ["item", "loading"])
  },
  created() {
    // Code ...
  },
  destroyed() {
    this.resetState();
  },
  methods: {
    ...mapActions("OrdersSingle", [
      "storeData",
      "resetState",
      "setBookNames",
      // "setBookCount",
      "setCountry",
      "setState",
      "setCity",
      "setZipCode",
      "setStreetAddress",
      "setSuiteNumber"
    ]),
    // updateBookName(e) {
    //   this.setBookName(e.target.value);
    // },
    // updateBookCount(e) {
    //   this.setBookCount(e.target.value);
    // },
    addBook() {
      this.books.push({
        name: null
      });
    },
    removeBook(index) {
      if (index != 0) {
        this.books.splice(index, 1);
      }
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
    updateSuiteNumber(e) {
      this.setSuiteNumber(e.target.value);
    },

    submitForm() {
      if (this.books.length == 0) {
        alert("Please add book!");
      }
      this.setBookNames(JSON.stringify(this.books));
      console.log(JSON.stringify(this.books));
      this.storeData()
        .then(() => {
          this.$router.push({
            name: "orders.index"
          });
          this.$eventHub.$emit("create-success");
        })
        .catch(error => {
          console.error(error);
        });
    }
  }
};
</script>

<style scoped>
.book-field {
  padding-bottom: 2px;
}
.remove-btn {
  font-size: 20px;
  cursor: pointer;
}
.add-btn {
  /* font-size: 18px; */
  cursor: pointer;
}
</style>


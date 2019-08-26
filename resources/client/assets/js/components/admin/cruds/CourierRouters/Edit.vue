<template>
  <section class="content-wrapper" style="min-height: 960px;">
    <section class="content-header">
      <h1>Courier Company Router</h1>
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
                  v-if="$can('courier_company_access')"
                  :to="{ name: 'courier_routers.index'}"
                  class="btn btn-default btn-sm"
                >
                  <span class="glyphicon glyphicon-chevron-left"></span> Back to all Courier Companies
                </router-link>
                <button
                  type="button"
                  class="btn btn-default btn-sm"
                  @click="fetchData($route.params.id)"
                >
                  <i class="fa fa-refresh" :class="{'fa-spin': loading}"></i> Refresh
                </button>
              </div>
              <div class="row" v-if="loading">
                <div class="col-xs-4 col-xs-offset-4">
                  <div class="alert text-center">
                    <i class="fa fa-spin fa-refresh"></i> Loading
                  </div>
                </div>
              </div>

              <bootstrap-alert />

              <div class="box-body" v-if="!loading">
                <div class="form-group">
                  <label for="continent">Select Continent *</label>
                  <v-select
                    @change="updateContinent"
                    :options="continents"
                    :value="item.continent"
                    :disabled="continents.length == 0"
                  ></v-select>
                </div>
                <div class="form-group">
                  <label for="country">Select Country</label>
                  <v-select
                    @change="updateCountry"
                    :options="countries"
                    :value="item.country"
                    :disabled="countries.length == 0"
                  >
                    <template slot="option" slot-scope="country">
                      <span class="country-label">{{ country.label }}</span>
                    </template>
                  </v-select>
                </div>
                <div class="form-group">
                  <label for="region">Select Region</label>
                  <v-select
                    @change="updateRegion"
                    :options="regions"
                    :value="item.region"
                    :disabled="regions.length == 0"
                  ></v-select>
                </div>
                <div class="form-group">
                  <label for="region">Select Printer Company *</label>
                  <v-select
                    :options="printer_companies"
                    @change="updatePrinterCompany"
                    :clearable="false"
                    label="name"
                    :disabled="printer_companies.length == 0"
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
                  <div>
                    <span style="font-weight:900">Select Courier Company *</span>
                    <i class="fas fa-plus-square add-btn" @click="addCourierField"></i>
                  </div>
                  <br />
                  <div
                    class="row col-md-8 col-xs-12 col-sm-12 courier-field"
                    v-for="(courier,index) in courierRelationShip"
                    v-bind:key="index"
                  >
                    <div>
                      <img
                        class="my-image"
                        :src="courier.courier_company.logo_url"
                        v-if="courier.courier_company"
                      />
                      <div class="col-md-1 col-xs-1 col-sm-1">
                        <i
                          v-if="index != 0"
                          class="fas fa-minus-square remove-btn pull-right"
                          @click="removeCourierField(index)"
                        ></i>
                      </div>
                      <div class="col-md-3 col-xs-7 col-sm-7">
                        <v-select
                          :options="courier_companies"
                          :clearable="false"
                          label="name"
                          v-model="courier.courier_company"
                        >
                          <template slot="option" slot-scope="courier_company">
                            <div class="custome-disabled">
                              <img
                                class="my-image"
                                :src="courier_company.logo_url"
                                v-if="courier_company.logo_url"
                              />
                              <span class="country-label">{{ courier_company.name }}</span>
                            </div>
                          </template>
                        </v-select>
                      </div>
                      <div class="col-md-3 col-xs-3 col-sm-3">
                        <input
                          type="number"
                          min="1"
                          max="100"
                          class="form-control"
                          name="percent"
                          placeholder="Enter Percent % "
                          v-model="courier.percent"
                          required
                        />
                      </div>
                    </div>
                  </div>
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
      courierRelationShip: []
    };
  },
  computed: {
    ...mapGetters("CourierRoutersSingle", [
      "item",
      "loading",
      "continents",
      "countries",
      "regions",
      "courier_companies",
      "printer_companies",
      "country_set_flag",
      "region_set_flag"
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
    ...mapActions("CourierRoutersSingle", [
      "updateData",
      "resetState",
      "fetchData",
      "fetchContinent",
      "fetchCountry",
      "fetchRegion",
      "fetchCourierCompany",
      "fetchPrinterCompany",
      "setContinent",
      "setCountry",
      "setRegion",
      "setCourierCompanies",
      "setPrinterCompany"
    ]),
    init() {
      this.resetState();
      this.fetchContinent();
      this.fetchCourierCompany();
      this.fetchPrinterCompany();
      this.fetchData(this.$route.params.id)
        .then(() => {
          for (var i in this.item.courier_companies) {
            var item = this.item.courier_companies[i];
            this.courierRelationShip.push({
              courier_company: {
                id: item.pivot.courier_company_id,
                name: item.name,
                logo_url: item.logo_url
              },
              percent: item.percent
            });
          }
          if (this.courierRelationShip.length == 0) {
            this.courierRelationShip.push({
              courier_company: null,
              percent: null
            });
          }
        })
        .catch(error => {
          console.error(error);
        });
    },
    addCourierField() {
      if (this.courierRelationShip.length >= this.courier_companies.length) {
        return;
      }
      this.courierRelationShip.push({
        courier_company: null,
        percent: null
      });
    },
    removeCourierField(index) {
      if (index != 0) {
        this.courierRelationShip.splice(index, 1);
      }
    },
    updateContinent(value) {
      console.log(value);
      this.setContinent(value);
      if (!this.country_set_flag) {
        this.setCountry(null);
      }

      this.fetchCountry(value);
    },
    updateCountry(value) {
      console.log(value);
      this.setCountry(value);
      if (!this.region_set_flag) {
        this.setRegion(null);
      }
      this.fetchRegion(value);
    },
    updateRegion(value) {
      console.log(value);
      this.setRegion(value);
    },
    updatePrinterCompany(value) {
      this.setPrinterCompany(value);
    },
    updateCourierCompany(value) {
      // console.log(value);
      // this.setCourierCompany(value);
    },
    updatePercent(e) {
      // this.setPercent(e.target.value);
    },
    makeRelation() {
      var courierMap = [];
      var totalPercent = 0;
      for (var i in this.courierRelationShip) {
        var courier = this.courierRelationShip[i].courier_company;
        if (courier == null) {
          continue;
        }
        if (
          courierMap
            .map(function(e) {
              return e.id;
            })
            .indexOf(courier.id) != -1
        ) {
          alert(courier.name + " is duplicated!");
          return false;
        }
        courierMap.push({
          id: courier.id,
          percent: Number(this.courierRelationShip[i].percent)
        });

        totalPercent += Number(this.courierRelationShip[i].percent);
      }
      console.log(totalPercent);
      if (totalPercent != 100) {
        alert("Total percent must be 100! But now it is " + totalPercent);
        return false;
      }
      console.log(courierMap);
      this.setCourierCompanies(courierMap);
      return true;
    },
    submitForm() {
      if (!this.makeRelation()) {
        return;
      }
      this.updateData()
        .then(() => {
          this.$router.push({ name: "courier_routers.index" });
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
  max-width: 50px;
  /* height: 50px; */
  display: inline-block;
  border-radius: 3px;
}
.remove-btn {
  font-size: 30px;
  cursor: pointer;
}
.add-btn {
  /* font-size: 18px; */
  cursor: pointer;
}
.courier-field {
  padding-bottom: 2px;
}
.custome-disabled {
  pointer-events: none;
  cursor: not-allowed;
}
</style>


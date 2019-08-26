function initialState() {
    return {
        item: {
            id: null,
            continent: null,
            country: null,
            region: null,
            courier_companies: null,
            printer_company: null,
        },
        exceptField: ['printer_company', 'updated_at', 'created_at'],
        continents: [],
        countries: [],
        regions: [],
        courier_companies: [],
        printer_companies: [],
        country_set_flag: false,
        region_set_flag: false,
        loading: false,
    }
}

const getters = {
    item: state => state.item,
    loading: state => state.loading,
    continents: state => state.continents,
    countries: state => state.countries,
    regions: state => state.regions,
    courier_companies: state => state.courier_companies,
    printer_companies: state => state.printer_companies,
    country_set_flag: state => state.country_set_flag,
    region_set_flag: state => state.region_set_flag,

}

const actions = {
    storeData({ commit, state, dispatch }) {
        commit('setLoading', true)
        dispatch('Alert/resetState', null, { root: true })

        return new Promise((resolve, reject) => {
            let params = new FormData();

            for (let fieldName in state.item) {
                let fieldValue = state.item[fieldName];
                if (state.exceptField.indexOf(fieldName) != -1) {
                    continue;
                }
                if (typeof fieldValue !== 'object') {
                    params.set(fieldName, fieldValue);
                } else {
                    if (fieldValue && typeof fieldValue[0] !== 'object') {
                        params.set(fieldName, fieldValue);
                    } else {
                        for (let index in fieldValue) {
                            params.set(fieldName + '[' + index + ']', fieldValue[index]);
                        }
                    }
                }
            }
            // if (state.item.continent) {
            //     params.set('continent', state.item.continent.code);
            // }

            // if (state.item.country) {
            //     params.set('country', state.item.country.code);
            // }

            // if (state.item.region) {
            //     params.set('region', state.item.region.code);
            // }
            // if (state.item.percent) {
            //     params.set('percent', state.item.percent);
            // }
            if (state.item.printer_company) {
                params.set('printer_company_id', state.item.printer_company.id);
            }


            axios.post('/api/v1/courier-routers', params)
                .then(response => {
                    commit('resetState')
                    resolve()
                })
                .catch(error => {
                    let message = error.response.data.message || error.message
                    let errors = error.response.data.errors

                    dispatch(
                        'Alert/setAlert', { message: message, errors: errors, color: 'danger' }, { root: true })

                    reject(error)
                })
                .finally(() => {
                    commit('setLoading', false)
                })
        })
    },
    updateData({ commit, state, dispatch }) {
        commit('setLoading', true)
        dispatch('Alert/resetState', null, { root: true })

        return new Promise((resolve, reject) => {
            let params = new FormData();
            params.set('_method', 'PUT')

            for (let fieldName in state.item) {
                console.log(fieldName);
                let fieldValue = state.item[fieldName];
                if (state.exceptField.indexOf(fieldName) != -1) {
                    continue;
                }

                if (typeof fieldValue !== 'object') {
                    params.set(fieldName, fieldValue);
                } else {
                    if (fieldValue && typeof fieldValue[0] !== 'object') {
                        params.set(fieldName, fieldValue);
                    } else {
                        for (let index in fieldValue) {
                            params.set(fieldName + '[' + index + ']', fieldValue[index]);
                        }
                    }
                }
            }

            if (state.item.printer_company) {
                params.set('printer_company_id', state.item.printer_company.id);
            }

            axios.post('/api/v1/courier-routers/' + state.item.id, params)
                .then(response => {
                    commit('setItem', response.data.data)
                    resolve()
                })
                .catch(error => {
                    let message = error.response.data.message || error.message
                    let errors = error.response.data.errors

                    dispatch(
                        'Alert/setAlert', { message: message, errors: errors, color: 'danger' }, { root: true })

                    reject(error)
                })
                .finally(() => {
                    commit('setLoading', false)
                })
        })
    },
    fetchData({ commit, dispatch }, id) {
        commit('setLoading', true)
        return new Promise((resolve, reject) => {
            axios.get('/api/v1/courier-routers/' + id)
                .then(response => {
                    commit('setItem', response.data.data)
                    resolve()
                })
                .catch(error => {
                    let message = error.response.data.message || error.message
                    let errors = error.response.data.errors

                    dispatch('Alert/setAlert', { message: message, errors: errors, color: 'danger' }, { root: true })

                    reject(error)
                })
                .finally(() => {
                    commit('setLoading', false);
                })
        });

    },
    fetchContinent({ commit }) {
        axios.get('/api/v1/fetch-continents')
            .then(response => {
                commit('setContinentArray', response.data.data)
            })
            .finally(() => {
                //commit('setLoading', false);
            })
    },
    fetchCountry({ commit }, continentCode) {
        commit('setCountryArray', []);
        if (continentCode == null) {
            return;
        }
        axios.get('/api/v1/fetch-countries/' + continentCode)
            .then(response => {
                commit('setCountryArray', response.data.data)
            })
            .finally(() => {
                //commit('setLoading', false);
            })
    },
    fetchRegion({ commit }, countryCode) {
        commit('setRegionArray', []);
        if (countryCode == null) {
            return;
        }
        axios.get('/api/v1/fetch-regions/' + countryCode)
            .then(response => {
                commit('setRegionArray', response.data.data)
            })
            .finally(() => {
                //commit('setLoading', false);
            })
    },
    fetchCourierCompany({ commit }) {
        axios.get('/api/v1/courier-companies')
            .then(response => {
                commit('setCourierCompanyArray', response.data.data)
            })
            .finally(() => {
                //commit('setLoading', false);
            })
    },
    fetchPrinterCompany({ commit }) {
        axios.get('/api/v1/printer-companies')
            .then(response => {
                commit('setPrinterCompanyArray', response.data.data)
            })
            .finally(() => {
                //commit('setLoading', false);
            })
    },

    setContinent({ commit }, value) {
        commit('setContinent', value)
    },
    setCountry({ commit }, value) {
        commit('setCountry', value)
    },
    setRegion({ commit }, value) {
        commit('setRegion', value)
    },
    setCourierCompanies({ commit }, value) {
        commit('setCourierCompanies', value)
    },
    setPrinterCompany({ commit }, value) {
        commit('setPrinterCompany', value);
    },
    resetState({ commit }) {
        commit('resetState')
    }
}

const mutations = {
    setItem(state, item) {
        state.country_set_flag = item.country != null;
        state.region_set_flag = item.region != null;
        state.item = item

    },
    setContinent(state, value) {
        state.item.continent = value
    },
    setCountry(state, value) {
        state.country_set_flag = false;
        state.item.country = value
    },
    setRegion(state, value) {
        state.region_set_flag = false;
        state.item.region = value
    },
    setCourierCompanies(state, value) {
        state.item.courier_companies = JSON.stringify(value);
    },
    setPrinterCompany(state, value) {
        state.item.printer_company = value;
    },

    setContinentArray(state, value) {
        state.continents = value;
    },
    setCountryArray(state, value) {
        state.countries = value;
    },
    setRegionArray(state, value) {
        state.regions = value;
    },
    setCourierCompanyArray(state, value) {
        state.courier_companies = value;
    },
    setPrinterCompanyArray(state, value) {
        state.printer_companies = value;
    },

    setLoading(state, loading) {
        state.loading = loading
    },
    resetState(state) {
        state = Object.assign(state, initialState())
    }
}

export default {
    namespaced: true,
    state: initialState,
    getters,
    actions,
    mutations
}
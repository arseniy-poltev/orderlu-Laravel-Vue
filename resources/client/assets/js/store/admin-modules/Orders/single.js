function initialState() {
    return {
        item: {
            id: null,
            // book_name: null,
            // book_count: null,
            book_names: null,
            country: null,
            state: null,
            city: null,
            zip_code: null,
            street_address: null,
            suite_number: null,
        },
        printer_companies: [],
        courier_companies: [],

        loading: false,
    }
}

const getters = {
    item: state => state.item,
    loading: state => state.loading,
    printer_companies: state => state.printer_companies,
    courier_companies: state => state.courier_companies,
}

const actions = {
    storeData({ commit, state, dispatch }) {

        commit('setLoading', true)
        dispatch('Alert/resetState', null, { root: true })

        return new Promise((resolve, reject) => {
            let params = new FormData();

            for (let fieldName in state.item) {
                let fieldValue = state.item[fieldName];
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



            axios.post('/api/v1/orders', params)
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

            if (state.item.printer_company) {
                params.set('printer_company_id', state.item.printer_company.id);
            }
            if (state.item.courier_company) {
                params.set('courier_company_id', state.item.courier_company.id);
            }

            axios.post('/api/v1/orders/' + state.item.id, params)
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
        axios.get('/api/v1/orders/' + id)
            .then(response => {
                commit('setItem', response.data.data)
            })
            .finally(() => {
                commit('setLoading', false)
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
    fetchCourierCompany({ commit }) {
        axios.get('/api/v1/courier-companies')
            .then(response => {
                commit('setCourierCompanyArray', response.data.data)
            })
            .finally(() => {
                //commit('setLoading', false);
            })
    },

    setBookNames({ commit }, value) {
        commit('setBookNames', value)
    },
    // setBookCount({ commit }, value) {
    //     commit('setBookCount', value)
    // },
    setCountry({ commit }, value) {
        commit('setCountry', value)
    },
    setPrinterCompany({ commit }, value) {
        commit('setPrinterCompany', value);
    },
    setCourierCompany({ commit }, value) {
        commit('setCourierCompany', value);
    },
    setState({ commit }, value) {
        commit('setState', value)
    },
    setCity({ commit }, value) {
        commit('setCity', value)
    },
    setZipCode({ commit }, value) {
        commit('setZipCode', value)
    },
    setStreetAddress({ commit }, value) {
        commit('setStreetAddress', value)
    },
    setSuiteNumber({ commit }, value) {
        commit('setSuiteNumber', value)
    },
    resetState({ commit }) {
        commit('resetState')
    }
}

const mutations = {
    setItem(state, item) {
        state.item = item
    },
    setBookNames(state, value) {
        state.item.book_names = value
    },
    // setBookCount(state, value) {
    //     state.item.book_count = value
    // },
    setPrinterCompany(state, value) {
        state.item.printer_company = value;
    },
    setCourierCompany(state, value) {
        state.item.courier_company = value;
    },
    setCountry(state, value) {
        state.item.country = value
    },
    setPrinterCompanyArray(state, value) {
        state.printer_companies = value;
    },
    setCourierCompanyArray(state, value) {
        state.courier_companies = value;
    },

    setState(state, value) {
        state.item.state = value
    },
    setCity(state, value) {
        state.item.city = value
    },
    setZipCode(state, value) {
        state.item.zip_code = value
    },
    setStreetAddress(state, value) {
        state.item.street_address = value
    },
    setSuiteNumber(state, value) {
        state.item.suite_number = value
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
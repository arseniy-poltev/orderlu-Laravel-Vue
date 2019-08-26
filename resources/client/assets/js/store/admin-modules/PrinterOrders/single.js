function initialState() {
    return {
        item: {
            id: null,
            book_name: null,
            country: null,
            state: null,
            city: null,
            zip_code: null,
            street_address: null,
            suite_number: null,
            courier_company: null,
            assigned_at: null,
            printed_at: null,
            delivered_at: null,
            brother_books: null,
            status: null
        },


        loading: false,
    }
}

const getters = {
    item: state => state.item,
    loading: state => state.loading,
}

const actions = {

    fetchBookData({ commit, dispatch }, id) {
        commit('resetState')
        commit('setLoading', true)
        axios.get('/api/v2/printer-books/' + id)
            .then(response => {
                commit('setItem', response.data.data)
            })
            .finally(() => {
                commit('setLoading', false)
            })
    },
    fetchOrderData({ commit, dispatch }, id) {
        commit('resetState')
        commit('setLoading', true)

        axios.get('/api/v2/printer-orders/' + id)
            .then(response => {
                commit('setItem', response.data.data)
            })
            .finally(() => {
                commit('setLoading', false)
            })
    },
    changeStatusAsPrinting({ commit, state, dispatch }) {
        commit('setLoading', true)
        dispatch('Alert/resetState', null, { root: true })

        return new Promise((resolve, reject) => {
            let params = new FormData();
            params.set('_method', 'PUT')
            params.set('id', state.item.id)

            axios.post('/api/v2/printer-orders/set-printing', params)
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
        });
    },
    changeStatusAsPrinted({ commit, state, dispatch }) {
        commit('setLoading', true)
        dispatch('Alert/resetState', null, { root: true })

        return new Promise((resolve, reject) => {
            let params = new FormData();
            params.set('_method', 'PUT')
            params.set('id', state.item.id)

            axios.post('/api/v2/printer-orders/set-printed', params)
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
        });
    },

    resetState({ commit }) {
        commit('resetState')
    }
}

const mutations = {
    setItem(state, item) {
        state.item = item
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
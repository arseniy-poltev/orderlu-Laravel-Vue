function initialState() {
    return {
        allData: [],
        all: [],
        relationships: {

        },
        query: {},
        loading: false
    }
}
const typeOf = o => Object.prototype.toString.call(o).slice(8, -1).toLowerCase()
const getters = {

    data: state => {
        let rows = state.all

        if (state.query.sort) {
            rows = _.orderBy(state.all, state.query.sort, state.query.order)
        }
        if (typeOf(state.query['scan_code']) == 'string') {
            rows = rows.filter(row => {
                var code = state.query['scan_code'].toLowerCase();
                if (row['order_id'] && row['order_id'].toLowerCase().includes(code)) {
                    return true;
                }
                if (row['book_id'] && row['book_id'].toLowerCase().includes(code)) {
                    return true;
                }

                return false;
            })
        }
        ['country'].forEach(field => {
            switch (typeOf(state.query[field])) {
                case 'array':
                    rows = rows.filter(row => state.query[field].includes(row[field]))
                    break
                case 'string':
                    rows = rows.filter(row => row[field].toLowerCase().includes(state.query[field].toLowerCase()))
                    break
                default:
                    // nothing to do
                    break
            }
        });
        ['courier_company', 'printer_company'].forEach(field => {
            if (typeOf(state.query[field]) == 'string') {
                rows = rows.filter(row => row[field]['name'].toLowerCase().includes(state.query[field].toLowerCase()))
            }
        })

        return rows.slice(state.query.offset, state.query.offset + state.query.limit)
    },
    allData: state => state.all,
    total: state => state.all.length,
    loading: state => state.loading,
    relationships: state => state.relationships
}

const actions = {
    fetchBooksInLots({ commit, state }) {
        commit('resetState');
        commit('setLoading', true)

        axios.get('/api/v2/books-in-lots')
            .then(response => {

                commit('setAll', response.data.data)
            })
            .catch(error => {
                message = error.response.data.message || error.message
                commit('setError', message)
                console.log(message)
            })
            .finally(() => {
                commit('setLoading', false)
            })
    },
    fetchPrintingOrder({ commit, state }) {
        commit('resetState');
        commit('setLoading', true)

        axios.get('/api/v2/printing-orders')
            .then(response => {

                commit('setAll', response.data.data)
            })
            .catch(error => {
                message = error.response.data.message || error.message
                commit('setError', message)
                console.log(message)
            })
            .finally(() => {
                commit('setLoading', false)
            })
    },
    fetchFinishedOrder({ commit, state }) {
        commit('resetState');
        commit('setLoading', true)

        axios.get('/api/v2/finished-orders')
            .then(response => {

                commit('setAll', response.data.data)
            })
            .catch(error => {
                message = error.response.data.message || error.message
                commit('setError', message)
                console.log(message)
            })
            .finally(() => {
                commit('setLoading', false)
            })
    },

    fetchPrintedOrder({ commit }) {
        commit('resetState');
        commit('setLoading', true)

        axios.get('/api/v2/printed-orders')
            .then(response => {

                commit('setAll', response.data.data)
            })
            .catch(error => {
                message = error.response.data.message || error.message
                commit('setError', message)
                console.log(message)
            })
            .finally(() => {
                commit('setLoading', false)
            })
    },
    fetchData({ commit }) {
        commit('resetState');
        commit('setLoading', true)

        axios.get('/api/v2/all-orders')
            .then(response => {

                commit('setAll', response.data.data)
            })
            .catch(error => {
                message = error.response.data.message || error.message
                commit('setError', message)
                console.log(message)
            })
            .finally(() => {
                commit('setLoading', false)
            })
    },

    setQuery({ commit }, value) {
        commit('setQuery', purify(value))
    },
    resetState({ commit }) {
        commit('resetState')
    }
}

const mutations = {
    setAll(state, items) {
        state.all = items
    },
    setLoading(state, loading) {
        state.loading = loading
    },
    setQuery(state, query) {
        state.query = query
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
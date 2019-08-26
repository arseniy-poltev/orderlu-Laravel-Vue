function initialState() {
    return {
        all: [],
        relationships: {

        },
        query: {},
        loading: false,
        loadingCnt: false,
        pendingCnt: '',
        assigning: false,
    }
}

const getters = {
    data: state => {
        let rows = state.all

        if (state.query.sort) {
            rows = _.orderBy(state.all, state.query.sort, state.query.order)
        }

        return rows.slice(state.query.offset, state.query.offset + state.query.limit)
    },
    pendingCnt: state => state.pendingCnt,
    loadingCnt: state => state.loadingCnt,
    total: state => state.all.length,
    loading: state => state.loading,
    assigning: state => state.assigning,
    relationships: state => state.relationships
}

const actions = {
    fetchData({ commit, state }) {
        commit('setLoading', true)

        axios.get('/api/v2/lots')
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
    fetchPendingBookCnt({ commit }) {
        commit('setLoadingCnt', true)
        commit('setPendingCnt', '');

        axios.get('/api/v2/get-pending-books')
            .then(response => {
                // console.log(response);
                commit('setPendingCnt', response.data)
            })
            .catch(error => {
                message = error.response.data.message || error.message
                commit('setError', message)
                console.log(message)
            })
            .finally(() => {
                commit('setLoadingCnt', false)
            })
    },
    createNewLot({ commit, dispatch }, numberBooks) {
        commit('setAssigning', true)
        let params = new FormData();
        params.set('number_books', numberBooks);
        axios.post('/api/v2/lots', params)
            .then(response => {
                // console.log(response);
                commit('addItemToData', response.data.data.lot);
                commit('setPendingCnt', response.data.data.pendingCnt)
            })
            .catch(error => {
                message = error.response.data.message || error.message
                commit('setError', message)
                console.log(message)
            })
            .finally(() => {
                commit('setAssigning', false)
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
    addItemToData(state, value) {
        state.all = state.all.concat(value);
    },
    setLoading(state, loading) {
        state.loading = loading
    },
    setAssigning(state, loading) {
        state.assigning = loading
    },
    setLoadingCnt(state, loading) {
        state.loadingCnt = loading
    },
    setPendingCnt(state, value) {
        state.pendingCnt = Number(value);
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
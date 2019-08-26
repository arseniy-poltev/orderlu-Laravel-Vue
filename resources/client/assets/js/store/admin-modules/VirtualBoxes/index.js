function initialState() {
    return {
        all: [],
        relationships: {

        },
        query: {},
        loading: false,
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
                var books = row['books'];
                for (var i in books) {
                    var bookId = 'bok-' + books[i]['id'];
                    if (bookId.includes(code)) {
                        return true;
                    }
                }
                if (row['order_id']) {
                    return row['order_id'].toLowerCase().includes(code);
                }
                return code == '';


            })
        }


        // return rows.slice(state.query.offset, state.query.offset + state.query.limit)
        return rows
    },

    total: state => state.all.length,
    loading: state => state.loading,
    relationships: state => state.relationships
}

const actions = {
    fetchData({ commit, state }) {
        commit('setLoading', true)

        axios.get('/api/v2/virtual-boxes')
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
    publishOrder({ commit, state, dispatch }, id) {
        commit('setProcessing', id)

        return new Promise((resolve, reject) => {

            let params = new FormData();
            params.set('id', id)

            axios.post('/api/v2/virtual-boxes/publish', params)
                .then(response => {
                    //commit('setItem', response.data.data)
                    //resolve()
                    dispatch('fetchData')
                })
                .catch(error => {
                    message = error.response.data.message || error.message

                    console.log(message)

                    reject(error)
                })
                .finally(() => {
                    // commit('setLoading', false)
                })
        });
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
        console.log(items);
        state.all = items
    },

    setLoading(state, loading) {
        state.loading = loading
    },
    setProcessing(state, id) {
        for (var i in state.all) {
            if (state.all[i].id == id) {
                state.all[i].processing = true;
                return;
            }
        }
    },
    setItem(state, item) {
        for (var i in state.all) {
            if (state.all[i].id == item.id) {
                alert(item.id);
                state.all[i] = item;
                return;
            }
        }
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
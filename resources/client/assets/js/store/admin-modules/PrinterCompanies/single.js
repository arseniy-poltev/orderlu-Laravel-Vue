function initialState() {
    return {
        item: {
            id: null,
            name: null,
            country: null,
            state: null,
            city: null,
            zip_code: null,
            street_address: null,
            full_address: null,
            logo: null,
            logo_url: null,
            users: [],
        },

        usersAll: [],
        loading: false,
    }
}

const getters = {
    item: state => state.item,
    loading: state => state.loading,
    usersAll: state => state.usersAll,
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

            if (_.isEmpty(state.item.users)) {
                params.delete('users')
            } else {
                for (let index in state.item.users) {
                    params.set('users[' + index + ']', state.item.users[index].id)
                }
            }


            axios.post('/api/v1/printer-companies', params)
                .then(response => {
                    commit('resetState')
                    dispatch('ImageFileInput/clearImage', null, { root: true });
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

            if (_.isEmpty(state.item.users)) {
                params.delete('users')
            } else {
                for (let index in state.item.users) {
                    params.set('users[' + index + ']', state.item.users[index].id)
                }
            }

            axios.post('/api/v1/printer-companies/' + state.item.id, params)
                .then(response => {
                    commit('setItem', response.data.data)
                    dispatch('ImageFileInput/clearImage', null, { root: true });
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
        axios.get('/api/v1/printer-companies/' + id)
            .then(response => {
                commit('setItem', response.data.data)
            })
            .finally(() => {
                commit('setLoading', false);
            })
        dispatch('fetchUsersAll')
    },
    fetchUsersAll({ commit }) {
        axios.get('/api/v1/fetch-non-assigned-users')
            .then(response => {
                commit('setUsersAll', response.data.data)
            })
    },


    setName({ commit }, value) {
        commit('setName', value)
    },
    setFullAddress({ commit }, value) {
        commit('setFullAddress', value)
    },
    setCountry({ commit }, value) {
        commit('setCountry', value)
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
    setLogo({ commit }, value) {
        commit('setLogo', value)
    },
    setUsers({ commit }, value) {
        commit('setUsers', value)
    },
    resetState({ commit }) {
        commit('resetState')
    }
}

const mutations = {
    setItem(state, item) {
        state.item = item;
        state.usersAll = state.usersAll.concat(item.users);
    },
    setUsers(state, value) {
        state.item.users = value
    },
    setUsersAll(state, value) {
        state.usersAll = state.usersAll.concat(value);
    },
    setName(state, value) {
        state.item.name = value
    },
    setFullAddress(state, value) {
        state.item.full_address = value
    },
    setCountry(state, value) {
        state.item.country = value
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
    setLogo(state, value) {
        state.item.logo = value
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
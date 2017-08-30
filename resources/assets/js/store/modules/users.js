const state = {
    data: JSON.parse(localStorage.getItem('SOPPLIS_USER') || '{}')
}

const getters = {
    isAuthenticated: state => state.data.token && state.data.token.length > 0,
    authentication: state => `Bearer ${state.data.token}`,
}

const mutations = {

    SET_USER (state, user) {
        state.data = user;
    }
}

const actions = {

    login ({commit}, user) {
        return axios.post('sessions', user).then(response => {
            if (response.data.success === true) {
                commit('SET_USER', {name: response.data.name, token: response.data.token});
            }
        });
    },

    logout () {
        localStorage.clear();
    },

    updateUser ({commit, state}, name) {
        return axios.put('users', {name: name}).then(response => {
            if (response.data.success === true) {
                let user = state.data;
                user.name = name;
                commit('SET_USER', user);
            }
        });
    }
}

export default {
    state,
    getters,
    mutations,
    actions
}

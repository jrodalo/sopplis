const state = {
    all: [],
};

const getters = {
    allFavorites: state => state.all,
    selectedFavorites: state => state.all.filter(item => item.selected),
    unselectedFavorites: state => state.all.filter(item => ! item.selected),
};

const mutations = {

    SET_FAVORITES (state, payload) {
        state.all = payload.items;
        localStorage.setItem(`SOPPLIS_${payload.list}_FAVS`, JSON.stringify(state.all));
    },
};

const actions = {

    fetchFavorites ({commit}, list) {

        commit('SET_FAVORITES', {list, items: JSON.parse(localStorage.getItem(`SOPPLIS_${list}_FAVS`) || '[]')});

        return axios.get(`lists/${list}/favorites`).then((response) => {
            commit('SET_FAVORITES', {list, items: response.data.items});
        });
    },

    insertSelected ({getters}, list) {

        let ids = getters.selectedFavorites.map(item => item.id).join(',');

        return axios.put(`lists/${list}/favorites`, {items: ids});
    },

    removeSelected ({commit, getters}, list) {

        let ids = getters.selectedFavorites.map(item => item.id).join(',');

        return axios.delete(`lists/${list}/favorites`, {params: {items: ids}}).then(response => {
            commit('SET_FAVORITES', {list, items: getters.unselectedFavorites});
        });
    },
};

export default {
    state,
    getters,
    mutations,
    actions,
};

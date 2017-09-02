const state = {
    all: []
}

const getters = {
    selected: state => state.all.filter(item => item.selected),
    unselected: state => state.all.filter(item => ! item.selected),
}

const mutations = {

    SET_FAVORITES (state, payload) {
        state.all = payload.items;
        localStorage.setItem(`SOPPLIS_${payload.list}_FAVS`, JSON.stringify(state.all));
    }

}

const actions = {

    fetchFavorites ({commit, state}, list) {

        commit('SET_FAVORITES', {list, items: JSON.parse(localStorage.getItem(`SOPPLIS_${list}_FAVS`) || '[]')});

        return axios.get(`lists/${list}/favorite`).then((response) => {
            commit('SET_FAVORITES', {list, items: response.data.items});
        });
    },

    insertSelected ({commit, getters}, list) {

        let ids = getters.selected.map(item => item.id).join(',');

        return axios.put(`lists/${list}/favorite`, {items: ids});
    },

    removeSelected ({commit, getters}, list) {

        let ids = getters.selected.map(item => item.id).join(',');

        return axios.delete(`lists/${list}/favorite`, {params: {items: ids}}).then(response => {
            commit('SET_FAVORITES', {list, items: getters.unselected});
        });
    }
}

export default {
    state,
    getters,
    mutations,
    actions
}

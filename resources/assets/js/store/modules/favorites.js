const state = {
    all: JSON.parse(localStorage.getItem('SOPPLIS_LISTS_FAVS') || '[]')
}

const mutations = {

    SET_FAVORITES (state, items) {
        state.all = items;
    }

}

const actions = {

    fetchFavorites ({commit}, list) {
        return axios.get(`lists/${list}/favorite`).then((response) => {
            commit('SET_FAVORITES', response.data.items);
        });
    },

    createList (context, list) {
        return axios.post('lists', list);
    }
}

export default {
    state,
    mutations,
    actions
}

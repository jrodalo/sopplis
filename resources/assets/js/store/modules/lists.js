const state = {
    all: JSON.parse(localStorage.getItem('SOPPLIS_LISTS') || '[]')
}

const mutations = {

    SET_LISTS (state, lists) {
        state.all = lists;
    }

}

const actions = {

    fetchLists ({commit}) {
        return axios.get('lists').then((response) => {
            commit('SET_LISTS', response.data.lists);
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

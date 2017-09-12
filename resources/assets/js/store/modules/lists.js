const state = {
    all: [],
};

const mutations = {

    SET_LISTS (state, lists) {
        state.all = lists;
        localStorage.setItem('SOPPLIS_LISTS', JSON.stringify(state.all));
    },

};

const actions = {

    fetchLists ({commit}) {

        commit('SET_LISTS', JSON.parse(localStorage.getItem('SOPPLIS_LISTS') || '[]'));

        return axios.get('lists').then((response) => {
            commit('SET_LISTS', response.data.lists);
        });
    },

    createList (context, list) {
        return axios.post('lists', list);
    },
};

export default {
    state,
    mutations,
    actions,
};

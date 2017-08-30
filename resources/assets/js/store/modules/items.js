const state = {
    all: JSON.parse(localStorage.getItem('SOPPLIS_LISTS') || '[]'),
    list: {}
}

const getters = {
    active: state => state.data.all.filter(item => ! item.done),
    completed: state => state.data.all.filter(item => item.done),
}

const mutations = {

    SET_ITEMS (state, items) {
        state.all = items;
    },

    SET_ITEM_LISTS (state, list) {
        state.list = list;
    },

    ADD_ITEM (state, item) {
        state.all.push(item);
    }
}

const actions = {

    fetchItems ({commit}, list) {
        return axios.get(`lists/${list}/items`).then((response) => {
            commit('SET_ITEMS', response.data.items);
            commit('SET_ITEM_LIST', response.data.list);
        });
    },

    insertItem ({commit}, list, item) {
        return axios.post(`lists/${list}/items`, item).then(response => {
            commit('ADD_ITEM', response.data.item);
        });
    },

    updateItem ({commit}, list, item) {
        return axios.put(`lists/${list}/items/${item.id}`, {done: item.done}).then(response => {
            //Item.writeCache(list, Item.state.items);
        });
    },

}

export default {
    state,
    getters,
    mutations,
    actions
}

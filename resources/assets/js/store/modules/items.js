const state = {
    all: [],
    list: {},
};

const getters = {
    active: state => state.all.filter(item => ! item.done),
    completed: state => state.all.filter(item => item.done),
    allDone: (state, getters) => state.all.length > 0 && (getters.completed.length === state.all.length),
    itemById: (state, getters) => id => state.all.find(item => item.id === id),
};

const mutations = {

    SET_ITEMS (state, payload) {
        state.all = payload.items;
        localStorage.setItem(`SOPPLIS_${payload.list}_ITEMS`, JSON.stringify(state.all));
    },

    SET_ITEM_LIST (state, list) {
        state.list = list;
    },

    ADD_ITEMS (state, payload) {
        state.all.push(...payload.items);
        localStorage.setItem(`SOPPLIS_${payload.list}_ITEMS`, JSON.stringify(state.all));
    },

    UPDATE_ITEM (state, payload) {
        let index = state.all.findIndex((item) => item.id === payload.item.id);
        state.all.splice(index, 1, payload.item);
        localStorage.setItem(`SOPPLIS_${payload.list}_ITEMS`, JSON.stringify(state.all));
    },

    CART_FINISHED (state, payload) {
        state.all = state.all.filter(item => payload.items.indexOf(item.id) < 0);
        localStorage.setItem(`SOPPLIS_${payload.list}_ITEMS`, JSON.stringify(state.all));
    },
};

const actions = {

    fetchItems ({commit}, list) {

        commit('SET_ITEMS', {list, items: JSON.parse(localStorage.getItem(`SOPPLIS_${list}_ITEMS`) || '[]')});

        return axios.get(`lists/${list}/items`).then((response) => {
            commit('SET_ITEMS', {list, items: response.data.items});
            commit('SET_ITEM_LIST', response.data.list);
        });
    },

    insertItem ({commit}, payload) {
        return axios.post(`lists/${payload.list}/items`, payload.item).then(response => {
            commit('ADD_ITEMS', {list: payload.list, items: [response.data.item]});
        });
    },

    updateItem ({commit}, payload) {
        return axios.put(`lists/${payload.list}/items/${payload.item.id}`, {done: payload.item.done}).then(response => {
            commit('UPDATE_ITEM', {list: payload.list, item: payload.item});
        });
    },

    deleteItems ({commit, getters}, payload) {

        let ids = getters.completed.map(item => item.id).join(',');

        return axios.delete(`lists/${payload.list}/items`, {params: {items: ids}}).then(response => {
            commit('SET_ITEMS', {list: payload.list, items: getters.active});
            return response;
        });
    },
};

export default {
    state,
    getters,
    mutations,
    actions,
};

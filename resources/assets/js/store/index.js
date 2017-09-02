import Vue from 'vue'
import Vuex from 'vuex'
import items from './modules/items'
import lists from './modules/lists'
import users from './modules/users'
import favorites from './modules/favorites'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        items,
        lists,
        favorites,
        users
    }
})

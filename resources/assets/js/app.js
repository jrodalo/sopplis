import Vue from 'vue'
import VueRouter from 'vue-router'
import User from './models/User'

window.axios = require('axios');
window.axios.defaults.baseURL='/api/v1';
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

if (User.isAuthenticated()) {
    window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + User.data().token;
}

window.sweetAlert = require('sweetalert');
window.sweetAlert.setDefaults({
                confirmButtonText: 'Si',
                cancelButtonText: 'No',
                animation: 'slide-from-bottom',
            });


Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'home',
            component: require('./views/Home.vue')
        },

        {
            path: '/lists',
            name: 'lists',
            component: require('./views/Lists.vue')
        },

        {
            path: '/config',
            name: 'config',
            component: require('./views/Config.vue')
        },

        {
            path: '/new',
            name: 'new',
            component: require('./views/New.vue')
        },

        {
            path: '/lists/:list',
            name: 'items',
            component: require('./views/Items.vue')
        },

        {
            path: '/lists/:list/favs',
            name: 'favs',
            component: require('./views/Favs.vue')
        }
    ]
})

const app = new Vue({
  router
}).$mount('#app')

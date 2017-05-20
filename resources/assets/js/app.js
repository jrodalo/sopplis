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
            name: 'login',
            component: require('./views/Login.vue'),
            beforeEnter: (to, from, next) => {
                if (User.isAuthenticated()) {
                    next({ name: 'lists' });
                } else {
                    next();
                }
            }
        },

        {
            path: '/lists',
            name: 'lists',
            component: require('./views/Lists.vue'),
            meta: { requiresAuth: true }
        },

        {
            path: '/config',
            name: 'config',
            component: require('./views/Config.vue'),
            meta: { requiresAuth: true }
        },

        {
            path: '/new',
            name: 'new',
            component: require('./views/New.vue'),
            meta: { requiresAuth: true }
        },

        {
            path: '/lists/:list',
            name: 'items',
            component: require('./views/Items.vue'),
            meta: { requiresAuth: true }
        },

        {
            path: '/lists/:list/favs',
            name: 'favs',
            component: require('./views/Favs.vue'),
            meta: { requiresAuth: true }
        },

        {
            path: '*',
            name: '404',
            component: require('./views/error/404.vue')
        }
    ]
});

router.beforeEach((to, from, next) => {

    if (to.matched.some(record => record.meta.requiresAuth)) {
        if ( ! User.isAuthenticated()) {
            next({
                name: 'login',
                query: { redirect: to.fullPath }
            })
        } else {
            next()
        }
    } else {
        next() // make sure to always call next()!
    }
});

const app = new Vue({
  router
}).$mount('#app')

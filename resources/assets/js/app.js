import Vue from 'vue';
import VueResource from 'vue-resource';
import VueRouter from 'vue-router';
import User from './user';
import SweetAlert from 'sweetalert';

Vue.use(VueRouter);
Vue.use(VueResource);

Vue.config.debug = true;
Vue.http.options.root = '/api/v1';
Vue.http.interceptors.push((request, next) => {

	if (User.isAuthenticated()) {
		request.headers['Authorization'] = 'Bearer ' + User.token();
	}

    next();
});

var router = new VueRouter({
	history: true
});

router.map({

	'/': {
		name: 'home',
		component: require('./pages/home.vue')
	},

	'/login': {
		name: 'login',
		component: require('./pages/login.vue')
	},

	'/lists': {
		name: 'lists',
		component: require('./pages/lists.vue'),
		auth: true
	},

	'/lists/new': {
		name: 'new',
		component: require('./pages/new.vue'),
		auth: true
	},

	'/lists/:list': {
		name: 'items',
		component: require('./pages/items.vue'),
		auth: true
	},

	'/lists/:list/favs': {
		name: 'favs',
		component: require('./pages/favs.vue'),
		auth: true
	},

	'*': {
		name: '404',
		component: require('./pages/404.vue')
	}

});

router.beforeEach(function (transition) {

	if (transition.to.auth && ! User.isAuthenticated()) {
		sweetAlert({
					  title: '¿Quién eres?',
					  text: 'Lo siento, no recuerdo quien eres... tienes que volver a entrar',
					  type: 'error',
					  animation: 'slide-from-bottom'});
		transition.redirect('/');
	} else {
		transition.next();
	}
});

var App = require('./pages/main.vue');

router.start(App, '#app');

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

	'/lists/:list': {
		name: 'items',
		component: require('./pages/items.vue'),
		auth: true
	},

	'*': {
		name: '404',
		component: require('./pages/404.vue')
	}

});

router.beforeEach(function (transition) {

	if (transition.to.auth && ! User.isAuthenticated()) {
		sweetAlert('¿Quién eres?', 'Lo siento, no recuerdo quien eres... tienes que volver a entrar', 'error');
		transition.redirect('/');
	} else {
		transition.next();
	}
});

var App = require('./pages/main.vue');

router.start(App, '#app');

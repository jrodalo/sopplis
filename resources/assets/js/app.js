import Vue from 'vue';
import VueResource from 'vue-resource';
import VueRouter from 'vue-router';

Vue.use(VueRouter);
Vue.use(VueResource);

//Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('content');
Vue.http.headers.common['Authorization'] = 'Bearer JQPR5gg6KSIKNFv3NyvUwKzEvSc7m6jNidRzViXmt6qsO9xR9SeDbGl2abY9';

var App = Vue.extend({});

var router = new VueRouter({
	history: true
});

router.map({

	'/': {
		name: 'login',
		component: require('./pages/login.vue')
	},

	'/lists': {
		name: 'lists',
		component: require('./pages/lists.vue')
	},

	'/lists/:slug': {
		name: 'items',
		component: require('./pages/items.vue')
	}

});

router.start(App, '#app');

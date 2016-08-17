<template>

	<div id="app" class="layout" v-cloak>
		<router-view></router-view>
	</div>

</template>

<script>

	import Vue from 'vue';
	import User from '../user';
	import SweetAlert from 'sweetalert';

	export default {

		created: function() {

			SweetAlert.setDefaults({
				confirmButtonText: 'Si',
				cancelButtonText: 'No',
				animation: 'slide-from-bottom',
			});

			Vue.config.debug = true;
			Vue.http.options.root = '/api/v1';
			Vue.http.interceptors.push((request, next) => {

				if (User.isAuthenticated()) {
					request.headers['Authorization'] = 'Bearer ' + User.data().token;
				}

			    next();
			});
			Vue.http.interceptors.push((request, next) => {
				next((response) => {
					if (response.status == 404) {
						this.$router.go({ path: '/404' });
					}
					if (response.status == 500) {
						this.$router.go({ path: '/error' });
					}
				})
			});

			var self = this;

			document.body.addEventListener('offline', function () {
				self.$broadcast('online', false);
			}, false);

			document.body.addEventListener('online', function () {
				self.$broadcast('online', true);
			}, false);

		}

	};

</script>

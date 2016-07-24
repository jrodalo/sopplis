<template>

	<section id="lists" class="page">
		<header class="header">
			<div class="header__content">
				<h1 class="header__title">Mis listas</h1>
				<a href="#" class="header__button" v-on:click="salir">+</a>
			</div>
		</header>

		<div :class="{'content': true, 'content--loading': $loadingRouteData}">
			<ul class="list">
				<li v-for="list in lists" class="item">
					<a v-link="{ name: 'items', params: { slug: list.slug }}">{{ list.name }}</a>
				</li>
			</ul>
		</div>

		<footer class="footer">

		</footer>
	</section>

</template>

<script>

	import SweetAlert from 'sweetalert';
	import User from '../user';

	export default {

		route: {

			data: function (transition) {

				return this.$http.get('lists').then((response) => {

					return {
						lists: response.json().lists
					};

				}, (response) => {
					sweetAlert('Oops...', 'No he podido leer tus listas... vuelve a intentarlo :(', 'error');

					return {
						lists: JSON.parse(localStorage.getItem('SOPPLIS_LISTS'))
					};

				});
			}
		},

		data: function() {
			return {
				lists: [],
				onLine: navigator.onLine
			}
		},

		watch: {
			lists: function(lists) {
				localStorage.setItem('SOPPLIS_LISTS', JSON.stringify(lists));
			}
		},

		methods: {

			salir: function() {
				User.logout();
			}
		},

		created: function() {
			window.addEventListener('online',  function(){
			    vm.onLine = true;
			});

			window.addEventListener('offline',  function(){
			    vm.onLine = false;
			});
		}
	};

</script>

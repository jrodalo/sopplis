<template>

	<section id="lists" class="page">
		<header class="header">
			<div class="header__content">
				<a href="#" class="header__button" v-on:click="salir">×</a>
				<h1 class="header__title">Mis listas</h1>
				<a href="#" class="header__button" v-on:click="salir">+</a>
			</div>
		</header>

		<div class="content">
			<ul class="list">
				<li v-for="list in state.lists" class="item">
					<a class="item__name" v-link="{ name: 'items', params: { list: list.slug }}">{{ list.name }}</a>
				</li>
			</ul>
			<loading v-show="$loadingRouteData"></loading>
		</div>
	</section>

</template>

<script>

	import SweetAlert from 'sweetalert';
	import User from '../user';
	import ListStore from '../liststore'

	export default {

		route: {

			data: function (transition) {
				return ListStore.readLists();
			}
		},

		data: function() {
			return {
				state: ListStore.state
			}
		},

		components: {
			loading: require('../components/loading.vue')
		},

		methods: {

			salir: function() {

				sweetAlert({
					  title: '¿Quieres salir de Sopplis?',
					  type: 'info',
					  showCancelButton: true,
					  confirmButtonText: 'Si',
					  cancelButtonText: 'No',
					  closeOnConfirm: true
					},
					function() {
						User.logout();
					}
				);
			}
		}
	};

</script>

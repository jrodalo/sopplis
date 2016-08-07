<template>

	<section id="lists" class="page">
		<header class="header">
			<div class="header__content">
				<a href="#" class="header__button" v-on:click="salir">×</a>
				<h1 class="header__title">Mis listas</h1>
				<a v-link="{ name: 'new' }" class="header__button">+</a>
			</div>
		</header>

		<div class="content">
			<ul class="list list--flex">
				<li v-for="list in state.lists" class="item item--taller">
					<a class="item__name" v-on:click.prevent="openList(list)">
						<span>{{ list.name }}</span>
						<i v-show="list.shared" title="Lista compartida">⚭</i>
					</a>
				</li>
			</ul>
		</div>
	</section>

</template>

<script>

	import SweetAlert from 'sweetalert';
	import User from '../user';
	import ListStore from '../liststore';
	import ItemStore from '../itemstore';

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

		methods: {

			openList: function(list) {
				ItemStore.currentList(list);
				this.$router.go({ name: 'items', params: { list: list.slug }});
			},

			salir: function() {

				var self = this;

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
						self.$router.go({ name: 'home' });
					}
				);
			}
		}
	};

</script>

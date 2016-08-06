<template>

	<section id="active-items" class="page">
		<header class="header">
			<create-item :list="list"></create-item>
		</header>

		<div class="content">
			<div v-show="state.items.length">
				<list-item :list="list"></list-item>
			</div>
			<div class="content--centered message--empty" v-show="!state.items.length && !$loadingRouteData">
				<h1 class="message__title">Esta lista está vacía :(</h1>
				<p>Añade los productos que quieres comprar pulsando el botón <b>+</b></p>
			</div>
		</div>

		<footer class="footer" v-show="state.items.length">
			<a href="#completed-items" :class="{footer__link: true, completed: allDone}" v-on:click.prevent="finalize" transition="fade">
				{{ completedItems.length }} de {{ state.items.length }}
			</a>
		</footer>
	</section>

</template>

<script>

	import SweetAlert from 'sweetalert';
	import ItemStore from '../itemstore';

	export default {

		route: {

			data: function (transition) {
				return ItemStore.readItems(this.list);
			}
		},

		data: function() {
			return {
				state: ItemStore.state,
				list: this.$route.params.list
			}
		},

		components: {
			createItem: require('../components/create-item.vue'),
			listItem: require('../components/list-item.vue'),
			loading: require('../components/loading.vue')
		},

		computed: {

			completedItems: function() {
				return ItemStore.readCompletedItems();
			},

			allDone: function() {
				return this.state.items.length > 0 && (this.completedItems.length == this.state.items.length);
			}
		},

		methods: {

			finalize: function() {

				if ( ! this.completedItems.length) {
					return;
				}

				var self = this;

				sweetAlert({
					  title: '¿Finalizar la compra?',
					  text: 'Se eliminarán los productos que hayas seleccionado',
					  type: 'info',
					  showCancelButton: true,
					  confirmButtonText: 'Si',
					  cancelButtonText: 'No',
					  closeOnConfirm: false,
					  showLoaderOnConfirm: true
					},
					function() {
						ItemStore.deleteItems(self.list, self.completedItems);
					}
				);
			}
		}
	};

</script>

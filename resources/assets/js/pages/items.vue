<template>

	<section id="active-items" class="page">
		<header class="header">
			<create-item :list="list"></create-item>
		</header>

		<div class="content">
			<list-item :list="list"></list-item>
			<loading v-show="$loadingRouteData"></loading>
		</div>

		<footer class="footer">
			<a href="#completed-items" class="footer__link" v-on:click.prevent="finalize">{{ completedItems.length }}</a>
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
			}
		},

		methods: {

			finalize: function() {

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

						var ids = self.completedItems.map(function(item) {
							return item.id;
						}).join(',');

						ItemStore.deleteItems(self.list, ids);
					}
				);
			}
		}
	};

</script>

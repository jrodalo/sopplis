<template>

	<section id="active-items" class="page">
		<header class="header">
			<create-item :slug="slug" :items.sync="items" :on-line="onLine"></create-item>
		</header>

		<div class="content">
			<list-item :slug="slug" :items.sync="items" :on-line="onLine"></list-item>
		</div>

		<footer class="footer">
			<a href="#completed-items" class="footer__link" v-on:click.prevent="finalize">{{ completedItems.length }}</a>
		</footer>
	</section>

</template>

<script>

	import SweetAlert from 'sweetalert';

	export default {

		data: function() {
			return {
				items: [],
				onLine: navigator.onLine,
				slug: this.$route.params.slug
			}
		},

		components: {
			createItem: require('../components/create-item.vue'),
			listItem: require('../components/list-item.vue')
		},

		computed: {

			completedItems: function() {
				return this.items.filter(function(item) {
					return item.done;
				});
			}
		},

		methods: {

				finalize: function() {

					if ( ! this.onLine || this.completedItems.length === 0) {
						return false;
					}

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

							setTimeout(function() {
								sweetAlert({
									title: '¡Finalizada!',
									timer: 2000,
									type: 'success',
		  							showConfirmButton: false});

								vm.items = [];
							}, 2000);
						}
					);
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

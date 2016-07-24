<template>

	<section id="active-items" class="page">
		<header class="header">
			<create-item :slug="slug" :items.sync="items" v-if="onLine"></create-item>
			<h1 class="header__title" v-else>No hay conexión a internet :(</h1>
		</header>

		<div :class="{'content': true, 'content--loading': $loadingRouteData}">
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

		route: {

			data: function (transition) {

				return this.$http.get('lists/' + this.$route.params.slug + '/items').then((response) => {

					return {
						items: response.json().items
					};

				}, (response) => {

					sweetAlert('Oops...', 'No he podido leer tu lista... vuelve a intentarlo :(', 'error');

					return {
						items: JSON.parse(localStorage.getItem('SOPPLIS_ITEMS_' + this.$route.params.slug))
					};
				});
			}
		},

		data: function() {
			return {
				items: [],
				onLine: navigator.onLine,
				slug: this.$route.params.slug
			}
		},

		watch: {
			items: function(items) {
				localStorage.setItem('SOPPLIS_ITEMS_' + this.$route.params.slug, JSON.stringify(items));
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

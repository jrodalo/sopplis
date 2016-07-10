import Vue from 'vue';
import VueResource from 'vue-resource';
import SweetAlert from 'sweetalert';

Vue.use(VueResource);
Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('content');

var vm = new Vue({

	el: '#app',

	data: {
			items: [],
			onLine: navigator.onLine
	},

	components: {
		createItem: require('./components/create-item.vue'),
		listItem: require('./components/list-item.vue')
	},

	computed: {

		completedItems: function() {
			return this.items.filter(function(item) {
				return item.completed;
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
	}

});

window.addEventListener('online',  function(){
    vm.onLine = true;
});

window.addEventListener('offline',  function(){
    vm.onLine = false;
});

import Vue from 'vue';

var ItemStore = {

	state: {
		items: []
	},


	readItems: function(list) {

		return Vue.http.get('lists/' + list + '/items').then((response) => {

			ItemStore.state.items = response.json().items;

		}, (response) => {

			sweetAlert('Oops...', 'No he podido leer tu lista... vuelve a intentarlo :(', 'error');

			ItemStore.state.items = JSON.parse(localStorage.getItem('SOPPLIS_ITEMS_' + list));
		});
	},


	readActiveItems: function() {

		return ItemStore.state.items.filter(function(item) {
			return ! item.done;
		});
	},

	readCompletedItems: function() {

		return ItemStore.state.items.filter(function(item) {
			return item.done;
		});
	},

	insertItem: function(list, item) {

		Vue.http.post('lists/' + list + '/items', item).then((response) => {

			ItemStore.state.items.push(response.json().item);
			//localStorage.setItem('SOPPLIS_ITEMS', JSON.stringify(this.items));

		}, (response) => {
			console.log('error' + response);
		});
	},

	updateItem: function(list, item) {

		Vue.http.put('lists/' + list + '/items/' + item.id, {done: item.done}).then((response) => {

		}, (response) => {

		});
	},

	deleteItems: function(list, items) {

		Vue.http.delete('lists/' + list + '/items', {params: {items: items}}).then((response) => {

			sweetAlert({
				title: '¡Finalizada!',
				timer: 2000,
				type: 'success',
				showConfirmButton: false});

			ItemStore.state.items = ItemStore.readActiveItems();

		}, (response) => {
			sweetAlert('Oops...', 'No he podido finalizar la compra... vuelve a intentarlo :(', 'error');
		});
	}

}

module.exports = ItemStore;

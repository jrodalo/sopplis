import Vue from 'vue';

var ItemStore = {

	state: {
		items: []
	},

	readCache: function(list) {
		return JSON.parse(localStorage.getItem('SOPPLIS_' + list + '_ITEMS')) || [];
	},

	writeCache: function(list, items) {
		localStorage.setItem('SOPPLIS_' + list + '_ITEMS', JSON.stringify(items));
	},

	readItems: function(list) {

		ItemStore.state.items = ItemStore.readCache(list);

		return Vue.http.get('lists/' + list + '/items').then((response) => {

			ItemStore.state.items = response.json().items;
			ItemStore.writeCache(list, ItemStore.state.items);

		}, (response) => {
			sweetAlert('Oops...', 'No he podido leer tu lista... vuelve a intentarlo :(', 'error');
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
			ItemStore.writeCache(list, ItemStore.state.items);

		}, (response) => {
			console.log('error' + response);
		});
	},

	updateItem: function(list, item) {

		Vue.http.put('lists/' + list + '/items/' + item.id, {done: item.done}).then((response) => {
			ItemStore.writeCache(list, ItemStore.state.items);
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
			ItemStore.writeCache(list, ItemStore.state.items);

		}, (response) => {
			sweetAlert('Oops...', 'No he podido finalizar la compra... vuelve a intentarlo :(', 'error');
		});
	}

}

module.exports = ItemStore;

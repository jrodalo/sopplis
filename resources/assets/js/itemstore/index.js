import Vue from 'vue';

var ItemStore = {

	state: {
		list: {},
		items: [],
		favorites: []
	},

	readCache: function(list, isFavorites) {

		var type = isFavorites ? '_FAVS' : '_ITEMS';

		return JSON.parse(localStorage.getItem('SOPPLIS_' + list + type)) || [];
	},

	writeCache: function(list, items, isFavorites) {

		var type = isFavorites ? '_FAVS' : '_ITEMS';

		localStorage.setItem('SOPPLIS_' + list + type, JSON.stringify(items));
	},

	readItems: function(list) {

		ItemStore.state.list = {};
		ItemStore.state.items = ItemStore.readCache(list);

		return Vue.http.get('lists/' + list + '/items').then((response) => {

			var json = response.json();
			ItemStore.state.items = json.items;
			ItemStore.state.list = json.list;
			ItemStore.writeCache(list, ItemStore.state.items);

		}, (response) => {
			sweetAlert('Oops...', 'No he podido leer tu lista... vuelve a intentarlo :(', 'error');
		});
	},

	readFavorites: function(list) {

		ItemStore.state.favorites = ItemStore.readCache(list, true);

		return Vue.http.get('lists/' + list + '/favorite').then((response) => {

			ItemStore.state.favorites = response.json().items;
			ItemStore.writeCache(list, ItemStore.state.favorites, true);

		}, (response) => {
			sweetAlert('Oops...', 'No he podido leer tus productos frecuentes... vuelve a intentarlo :(', 'error');
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

		var ids = ItemStore.extractIds(items);

		Vue.http.delete('lists/' + list + '/items', {params: {items: ids}}).then((response) => {

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
	},

	addItems: function(list, items) {

		var ids = ItemStore.extractIds(items);

		return Vue.http.put('lists/' + list + '/favorite', {items: ids});
	},

	extractIds: function(items) {
		return items.map(function(item) {return item.id;}).join(',');
	}

}

module.exports = ItemStore;

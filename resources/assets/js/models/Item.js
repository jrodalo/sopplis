
var Item = {

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

		Item.state.list = {};
		Item.state.items = Item.readCache(list);

		return axios.get('lists/' + list + '/items').then(response => {
			var json = response.data;
			Item.state.list = json.list;
			Item.state.items = json.items;
			Item.writeCache(list, Item.state.items);
		});
	},

	readFavorites: function(list) {

		Item.state.favorites = Item.readCache(list, true);

		return axios.get('lists/' + list + '/favorite').then(response => {
			Item.state.favorites = response.data.items;
			Item.writeCache(list, Item.state.favorites, true);
		});
	},

	readActiveItems: function() {
		return Item.state.items.filter(function(item) {
			return ! item.done;
		});
	},

	readCompletedItems: function() {
		return Item.state.items.filter(function(item) {
			return item.done;
		});
	},

	insertItem: function(list, item) {
		return axios.post('lists/' + list + '/items', item).then(response => {
			Item.state.items.push(response.data.item);
			Item.writeCache(list, Item.state.items);
			return response;
		});
	},

	updateItem: function(list, item) {
		return axios.put('lists/' + list + '/items/' + item.id, {done: item.done}).then(response => {
			Item.writeCache(list, Item.state.items);
			return response;
		});
	},

	deleteItems: function(list, items) {

		var ids = Item.extractIds(items);

		return axios.delete('lists/' + list + '/items', {params: {items: ids}}).then(response => {
			Item.state.items = Item.readActiveItems();
			Item.writeCache(list, Item.state.items);
			return response;
		});
	},

	deleteFavorites: function(list, items) {

		var ids = Item.extractIds(items);

		return axios.delete('lists/' + list + '/favorite', {params: {items: ids}}).then(response => {
			Item.state.favorites = Item.state.favorites.filter(function(item) {
				return ! item.selected;
			});
			Item.writeCache(list, Item.state.favorites, true);
			return response;
		});
	},

	addFavorites: function(list, items) {
		var ids = Item.extractIds(items);
		return axios.put('lists/' + list + '/favorite', {items: ids});
	},

	extractIds: function(items) {
		return items.map(function(item) {return item.id;}).join(',');
	}

}

export default Item;

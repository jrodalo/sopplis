
const Item = {

	state: {
		loading: false,
		list: {},
		items: [],
		favorites: []
	},

	readCache (list, isFavorites) {

		let type = isFavorites ? '_FAVS' : '_ITEMS';

		return JSON.parse(localStorage.getItem('SOPPLIS_' + list + type)) || [];
	},

	writeCache (list, items, isFavorites) {

		let type = isFavorites ? '_FAVS' : '_ITEMS';

		localStorage.setItem('SOPPLIS_' + list + type, JSON.stringify(items));
	},

	readItems (list) {

		Item.state.loading = true;
		Item.state.list = {};
		Item.state.items = Item.readCache(list);

		return axios.get('lists/' + list + '/items').then(response => {
			Item.state.loading = false;
			Item.state.list = response.data.list;
			Item.state.items = response.data.items;
			Item.writeCache(list, Item.state.items);
		});
	},

	readFavorites (list) {

		Item.state.favorites = Item.readCache(list, true);

		return axios.get('lists/' + list + '/favorite').then(response => {
			Item.state.favorites = response.data.items;
			Item.writeCache(list, Item.state.favorites, true);
		});
	},

	readActiveItems () {
		return Item.state.items.filter(item => ! item.done);
	},

	readCompletedItems () {
		return Item.state.items.filter(item => item.done);
	},

	insertItem (list, item) {
		return axios.post('lists/' + list + '/items', item).then(response => {
			Item.state.items.push(response.data.item);
			Item.writeCache(list, Item.state.items);
			return response;
		});
	},

	updateItem (list, item) {
		return axios.put('lists/' + list + '/items/' + item.id, {done: item.done}).then(response => {
			Item.writeCache(list, Item.state.items);
			return response;
		});
	},

	deleteItems (list, items) {

		let ids = Item.extractIds(items);

		return axios.delete('lists/' + list + '/items', {params: {items: ids}}).then(response => {
			Item.state.items = Item.readActiveItems();
			Item.writeCache(list, Item.state.items);
			return response;
		});
	},

	deleteFavorites (list, items) {

		let ids = Item.extractIds(items);

		return axios.delete('lists/' + list + '/favorite', {params: {items: ids}}).then(response => {
			Item.state.favorites = Item.state.favorites.filter(item => ! item.selected);
			Item.writeCache(list, Item.state.favorites, true);
			return response;
		});
	},

	addFavorites (list, items) {
		let ids = Item.extractIds(items);
		return axios.put('lists/' + list + '/favorite', {items: ids});
	},

	extractIds (items) {
		return items.map(item => item.id).join(',');
	}

}

export default Item;

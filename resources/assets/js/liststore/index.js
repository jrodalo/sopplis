import Vue from 'vue';

var ListStore = {

	state: {
		lists: []
	},

	readCache: function() {
		return JSON.parse(localStorage.getItem('SOPPLIS_LISTS')) || [];
	},

	writeCache: function(lists) {
		localStorage.setItem('SOPPLIS_LISTS', JSON.stringify(lists));
	},

	readLists: function() {

		ListStore.state.lists = ListStore.readCache();

		return Vue.http.get('lists').then((response) => {

			ListStore.state.lists = response.json().lists;
			ListStore.writeCache(ListStore.state.lists);

		}, (response) => {
			sweetAlert('Oops...', 'No he podido leer tus listas... vuelve a intentarlo :(', 'error');
		});
	},

	insertList: function(list) {

		Vue.http.post('lists', list).then((response) => {

			ListStore.state.lists.push(response.json().list);
			ListStore.writeCache(ListStore.state.lists);

		}, (response) => {
			sweetAlert('Oops...', 'No he podido crear tu lista... vuelve a intentarlo :(', 'error');
		});
	}

}

module.exports = ListStore;

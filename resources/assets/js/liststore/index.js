import Vue from 'vue';

var ListStore = {

	state: {
		lists: []
	},

	readLists: function() {

		return Vue.http.get('lists').then((response) => {

			ListStore.state.lists = response.json().lists;

		}, (response) => {
			sweetAlert('Oops...', 'No he podido leer tus listas... vuelve a intentarlo :(', 'error');

			ListStore.state.lists = JSON.parse(localStorage.getItem('SOPPLIS_LISTS'));
		});
	},

	insertList: function(list, item) {

		Vue.http.post('lists/' + list + '/items', item).then((response) => {

			ListStore.state.items.push(response.json().item);
			//localStorage.setItem('SOPPLIS_ITEMS', JSON.stringify(this.items));

		}, (response) => {
			console.log('error' + response);
		});
	}

}

module.exports = ListStore;

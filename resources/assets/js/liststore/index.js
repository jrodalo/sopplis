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
		});
	},

	addList: function(list) {
		return Vue.http.post('lists', list);
	},

	splitEmails: function(emails) {
		return (emails || '')
					.split(/\r*\n/)
					.filter(function(line) {
						return line && /^.*@.*\.[A-z]{2,3}$/.test(line);
					});
	}

}

module.exports = ListStore;

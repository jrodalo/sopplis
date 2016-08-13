import Vue from 'vue';

var User = {

	isAuthenticated: function() {
		return this.token().length > 0;
	},

	data: function() {
		return JSON.parse(localStorage.getItem('SOPPLIS_USER')) || {};
	},

	token: function() {
		return User.data().token || '';
	},

	name: function() {
		return User.data().name || '';
	},

	updateUser: function(name) {
		return Vue.http.put('users', {name: name}).then(response => {
			if (response.ok) {
				var user = User.data();
				user.name = name;
				localStorage.setItem('SOPPLIS_USER', JSON.stringify(user));
			}
		});
	},

	login: function(name, token) {
		localStorage.setItem('SOPPLIS_USER', JSON.stringify({name: name, token: token}));
	},

	logout: function() {
		localStorage.clear();
	}

}

module.exports = User;

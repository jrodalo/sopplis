import Vue from 'vue';

var User = {

	isAuthenticated: function() {
		return this.data().token && this.data().token.length > 0;
	},

	data: function() {
		return JSON.parse(localStorage.getItem('SOPPLIS_USER')) || {};
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

	login: function(user) {
		return Vue.http.post('users', user).then((response) => {

			var data = response.json();

			if (response.ok && data.success) {
				localStorage.setItem('SOPPLIS_USER', JSON.stringify({name: data.name, token: data.token}));
			}

			return response;
		});
	},

	logout: function() {
		localStorage.clear();
	}

}

module.exports = User;

var user = {

	isAuthenticated: function() {
		return this.token().length > 0;
	},

	token: function() {
		return localStorage.getItem('SOPPLIS_TOKEN') || '';
	},

	login: function(token) {
		localStorage.setItem('SOPPLIS_TOKEN', token);
	},

	logout: function() {
		localStorage.removeItem('SOPPLIS_TOKEN');
	}

}

module.exports = user;

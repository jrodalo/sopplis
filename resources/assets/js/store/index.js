var store = {

	state: {
		items: []
	},

	load: function() {

		if (navigator.onLine) {
			this.$http.get('/api/v1/lists/1132/items').then((response) => {
				this.state.items = response.json();
				//localStorage.setItem('SOPPLIS_ITEMS', JSON.stringify(this.items));
			}, (response) => {
				this.state.items = [];
			});
		} else {
			alert("no internet");
			this.state.items = JSON.parse(localStorage.getItem('SOPPLIS_ITEMS'));
		}
	},

	save: function (value) {

		console.log(this.$http);
		/*var parts = value.split(',');

		for (var i = 0; i < parts.length; i++) {

			var item = { 'name': parts[i], 'completed': false };

			this.state.items.push(item);
		}

		localStorage.setItem('SOPPLIS_ITEMS', JSON.stringify(this.items));*/
	}
}

module.exports = store;

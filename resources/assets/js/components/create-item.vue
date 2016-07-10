<template>
	<form class="form" v-on:submit.prevent="addItem" v-show="onLine">
		<input class="form__input"
					autofocus
					autocomplete="off"
					placeholder="¿Qué necesitas comprar?"
					v-model="newItem">
		<i class="form__menu">☰</i>
	</form>

	<h1 class="header__title" v-else>No hay conexión a internet :(</h1>

</template>

<script>

	export default {

		props: {
			items: { required: true, type: Array },
			onLine: { required: true, type: Boolean}
		},

		data: function() {
			return {
				newItem: ''
			};
		},

		methods: {

			addItem: function () {

				var value = this.newItem && this.newItem.trim();
				if (!value) { return; }

				var parts = value.split(',');

				for (var i = 0; i < parts.length; i++) {

					var item = { 'id': new Date().getTime(), 'name': parts[i], 'completed': false };

					this.items.push(item);
				}

				this.newItem = '';

				localStorage.setItem('SOPPLIS_ITEMS', JSON.stringify(this.items));
			}
		}

	};

</script>

<style lang="sass">

	@import "resources/assets/sass/_variables";

	.form {display: flex; margin: 10px;}
	.form__input {border: 0; border-radius: 3px 0 0 3px; font-size: 1em; padding: 0 10px; height: 35px; background: $light-color; color: #FFF; flex: 1;}
	.form__input::placeholder {color: #FFF; opacity: 1; text-align: center;}
	.form__input:focus {outline: 0;}

	.form__menu {padding: 5px 15px; text-align: center; background: lighten($light-color, 12); border-radius: 0 3px 3px 0; font-style: normal; cursor: pointer;}

	.header__title {color: #FFF; text-align: center; font-size: 1em; line-height: 55px;}
</style>

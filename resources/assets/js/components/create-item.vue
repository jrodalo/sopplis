<template>

	<div class="header__content" v-show="!editing">
		<a v-link="{ name: 'lists' }" class="header__button">❰</a>
		<h1 class="header__title">Lista de la compra</h1>
		<a href="#" class="header__button" v-on:click.prevent="toggleCreateItem">+</a>
	</div>

	<form class="form" v-on:submit.prevent="addItem" v-show="editing">
		<input class="form__input"
				autocomplete="off"
				placeholder="¿Qué necesitas comprar?"
				maxlength="100"
				v-model="newItem"
				v-on:blur="toggleCreateItem">
		<a href="#" class="header__button header__button--side">☰</a>
	</form>
</template>

<script>

	export default {

		props: {
			slug: { required: true },
			items: { required: true, type: Array }
		},

		data: function() {
			return {
				newItem: '',
				editing: false
			};
		},

		methods: {

			toggleCreateItem: function() {
				this.editing = ! this.editing;

				if (this.editing) {
					document.querySelectorAll('.form__input')[0].focus();
				}
			},

			addItem: function () {

				var value = this.newItem && this.newItem.trim();
				if (!value) { return; }

				var resource = this.$resource('lists/' + this.slug + '/items');
				var self = this;

				resource.save({name: value}).then((response) => {
					this.$get('items').push(response.json().item);
					//self.items.push(response.json().item);
					self.newItem = '';
					localStorage.setItem('SOPPLIS_ITEMS', JSON.stringify(self.items));
				}, (response) => {
					console.log('error' + response);
				});
			}
		}

	};

</script>

<style lang="sass">

	@import "resources/assets/sass/_variables";

	.form {display: flex; margin: 10px;}
	.form__input {border: 0; border-radius: 3px 0 0 3px; font-size: 1em; padding: 0 10px; background: $light-color; color: #FFF; flex: 1;}
	.form__input::placeholder {color: #FFF; opacity: 1; text-align: center;}
	.form__input:focus {outline: 0;}

	.header__button--side {border-radius: 0 3px 3px 0;}

</style>

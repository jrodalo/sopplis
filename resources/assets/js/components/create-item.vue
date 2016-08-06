<template>

	<div class="header__content" v-show="!editing">
		<a v-link="{ name: 'lists' }" class="header__button">«</a>
		<h1 class="header__title">Lista de la compra</h1>
		<a href="#" class="header__button" v-on:click.prevent="toggleCreateItem">+</a>
	</div>

	<form class="form" v-on:submit.prevent="addItem" v-show="editing">
		<input class="form__input"
				autocomplete="off"
				placeholder="¿Qué necesitas comprar?"
				maxlength="100"
				v-model="newItem"
				v-on:blur="toggleCreateItem"
				v-el:item-input>
		<a href="#" class="header__button header__button--side">☰</a>
	</form>
</template>

<script>

	import Vue from 'vue';
	import ItemStore from '../itemstore';

	export default {

		props: {
			list: { required: true }
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
					var self = this;
					Vue.nextTick(function () {
						self.$els.itemInput.focus();
					});
				}
			},

			addItem: function () {

				var value = this.newItem && this.newItem.trim();
				if (!value) { return; }

				var item = {name: value};

				ItemStore.insertItem(this.list, item);

				this.newItem = '';
			}
		}

	};

</script>

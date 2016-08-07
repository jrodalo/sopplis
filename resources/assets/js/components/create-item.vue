<template>

	<div class="header__content" v-show="!editing">
		<a v-link="{ name: 'lists' }" class="header__button">«</a>
		<h1 class="header__title" v-text="state.list.name"></h1>
		<a href="#" class="header__button" v-on:click.prevent="showForm">+</a>
	</div>

	<form class="header-form" v-on:submit.prevent="addItem" v-show="editing">
		<input class="header-form__input"
				autocomplete="off"
				placeholder="¿Qué necesitas comprar?"
				maxlength="100"
				v-model="newItem"
				v-on:blur="hideForm"
				v-el:item-input>
		<a v-link="{ name: 'favs', params: {list: list} }" class="header__button header__button--side">☰</a>
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
				editing: false,
				state: ItemStore.state
			};
		},

		methods: {

			showForm: function() {
				this.editing = true;
				var self = this;
				Vue.nextTick(function () {
					self.$els.itemInput.focus();
				});
			},

			hideForm: function() {
				setTimeout(function() {
					this.editing = false;
				}.bind(this), 500);
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

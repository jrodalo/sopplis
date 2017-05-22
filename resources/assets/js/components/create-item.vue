<template>

	<div>
		<div class="header__content" v-show="!editing">
			<router-link :to="{ name: 'lists' }" class="header__button">«</router-link>
			<transition name="fade">
				<h1 :class="{'header__title': true, 'hidden': ! state.list.name}" v-text="state.list.name"></h1>
			</transition>
			<a href="#" class="header__button" v-on:click.prevent="showForm">+</a>
		</div>

		<form class="header-form" v-on:submit.prevent="addItem" v-show="editing">
			<input class="header-form__input"
					autocomplete="off"
					placeholder="¿Qué necesitas comprar?"
					maxlength="100"
					ref="itemInput"
					v-model="newItem"
					v-on:blur="hideForm">
			<router-link :to="{ name: 'favs', params: {list: list} }" class="header__button header__button--side">★</router-link>
		</form>
	</div>
</template>

<script>

	import Vue from 'vue';
	import Item from '../models/Item';

	export default {

		props: {
			list: { required: true }
		},

		data () {
			return {
				newItem: '',
				editing: false,
				state: Item.state
			};
		},

		methods: {

			showForm () {
				this.editing = true;
				Vue.nextTick(() => this.$refs.itemInput.focus());
			},

			hideForm () {
				setTimeout(() => this.editing = false, 500);
			},

			addItem () {

				let value = this.newItem && this.newItem.trim();
				if (!value) { return; }

				let item = {name: value};

				Item.insertItem(this.list, item);

				this.newItem = '';
			}
		}

	};

</script>

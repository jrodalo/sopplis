<template>

	<div>
		<div class="header__content" v-show="!editing">
			<router-link :to="{ name: 'lists' }" class="header__button" aria-label="Volver">«</router-link>
			<transition name="fade">
				<h1 :class="{'header__title': true, 'hidden': ! listName}" v-text="listName"></h1>
			</transition>
			<a href="#" class="header__button" v-on:click.prevent="showForm" aria-label="Añadir">+</a>
		</div>

		<form class="header-form" v-on:submit.prevent="addItem" v-show="editing">
			<input class="header-form__input"
					autocomplete="off"
					placeholder="¿Qué necesitas comprar?"
					maxlength="100"
					ref="itemInput"
					v-model.trim="name"
					v-on:blur="hideForm">
			<router-link :to="{ name: 'favs', params: {list: list} }" class="header__button header__button--side">★</router-link>
		</form>
	</div>
</template>

<script>

	import Vue from 'vue';

	export default {

		props: {
      		list: { required: true }
    	},

		data () {
			return {
				name: '',
				editing: false
			};
		},

        computed: {

            listName () {
                return this.$store.state.items.list.name;
            }

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

				if (!this.name) { return; }

				let item = {name: this.name};

				if (this.$store.state.items.all.find((existing) => existing.name === item.name)) {
					this.name = '';
					return;
				}

				this.$store.dispatch('insertItem', {list: this.list, item});

				this.name = '';
			}
		},
	};

</script>

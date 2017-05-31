<template>

	<section id="fav-items" class="page">
		<header class="header">
			<div class="header__content">
				<router-link :to="{ name: 'items' }" class="header__button">«</router-link>
				<h1 class="header__title">Productos frecuentes</h1>
				<a v-show="state.favorites.length" v-on:click="changeMode" :class="{'header__button': true, 'header__button--pressed': mode == 'remove'}">✎</a>
			</div>
		</header>

		<div class="content content--withfooter">
			<div v-show="state.favorites.length" :class="{remove: mode == 'remove', add: mode == 'add'}">
				<ul class="list list--flex">
					<li
						v-for="item in state.favorites"
						v-on:click="select(item)"
						:class="{'item': true, 'item--fav': true, 'item--selected': item.selected}"
						>{{ item.name }}</span></li>
				</ul>
			</div>
			<div class="content--centered message message--empty" v-show="!state.favorites.length && !state.loading">
				<h1 class="message__title">No hay productos frecuentes</h1>
				<p>Los productos frecuentes son aquellos que has comprado varias veces. Sigue utilizando Sopplis para que aparezcan aquí los productos que más compras.</p>
			</div>
		</div>

		<footer class="footer" v-show="state.favorites.length">
			<div v-show="mode=='add'">
				<a href="#fav-items" class="footer__link" v-on:click.prevent="addSelected">
					<span v-show="selected.length">Añadir {{ selected.length}} productos</span>
					<span v-show="!selected.length">Selecciona los que quieras añadir</span>
				</a>
			</div>
			<div v-show="mode!='add'">
				<a href="#fav-items" class="footer__link footer__link--red" v-on:click.prevent="removeSelected">
					<span v-show="selected.length">Eliminar {{ selected.length}} productos</span>
					<span v-show="!selected.length">Selecciona los que quieras eliminar</span>
				</a>
			</div>
		</footer>

	</section>

</template>

<script>

	import Item from '../models/Item';

	export default {

		props: {
			list: { required: true }
		},

		created () {
			this.fetchData();
		},

		data () {
			return {
				state: Item.state,
				mode: 'add'
			}
		},

		computed: {

			selected () {
				return this.state.favorites.filter(function(item) {
					return item.selected;
				});
			}
		},

		methods: {

			fetchData () {
				return Item.readFavorites(this.list);
			},

			changeMode () {
				this.mode = (this.mode == 'add') ? 'remove' : 'add';
			},

			select (item) {
				item.selected = ! item.selected;
			},

			addSelected () {

				let itemsSelected = this.selected;

				if ( ! itemsSelected.length) {
					return;
				}

				Item.addFavorites(this.list, itemsSelected).then(response => {
					this.$router.push({ name: 'items', params: {list: this.list} })
				}, response => {

				});
			},

			removeSelected () {

				let itemsSelected = this.selected;

				if ( ! itemsSelected.length) {
					return;
				}

				Item.deleteFavorites(this.list, itemsSelected).then(response => {
					this.mode = 'add';
				}, response => {

				});
			}

		}
	};

</script>

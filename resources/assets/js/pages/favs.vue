<template>

	<section id="fav-items" class="page">
		<header class="header">
			<div class="header__content">
				<a v-link="{ name: 'items' }" class="header__button">«</a>
				<h1 class="header__title">Productos frequentes</h1>
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
			<div class="content--centered message message--empty" v-show="!state.favorites.length && !$loadingRouteData">
				<h1 class="message__title">No hay productos frecuentes</h1>
				<p>Los productos frequentes son aquellos que has comprado varias veces. Sigue utilizando Sopplis para que aparezcan aquí los productos que más compras.</p>
			</div>
		</div>

		<footer class="footer" v-show="state.favorites.length">
			<div v-show="mode=='add'">
				<a class="footer__link" v-on:click.prevent="addSelected">
					<span v-show="selected.length">Añadir {{ selected.length}} {{ selected.length | pluralize 'producto' }}</span>
					<span v-else>Selecciona los que quieras añadir</span>
				</a>
			</div>
			<div v-else>
				<a class="footer__link footer__link--red" v-on:click.prevent="removeSelected">
					<span v-show="selected.length">Eliminar {{ selected.length}} {{ selected.length | pluralize 'producto' }}</span>
					<span v-else>Selecciona los que quieras eliminar</span>
				</a>
			</div>
		</footer>

	</section>

</template>

<script>

	import ItemStore from '../itemstore';

	export default {

		route: {

			data: function (transition) {
				return ItemStore.readFavorites(this.list);
			}
		},

		data: function() {
			return {
				state: ItemStore.state,
				list: this.$route.params.list,
				mode: 'add'
			}
		},

		computed: {
			selected: function() {
				return this.state.favorites.filter(function(item) {
					return item.selected;
				});
			}
		},

		methods: {

			changeMode: function() {
				this.mode = (this.mode == 'add') ? 'remove' : 'add';
			},

			select: function(item) {
				item.selected = ! item.selected;
			},

			addSelected: function() {

				var itemsSelected = this.selected;

				if ( ! itemsSelected.length) {
					return;
				}

				ItemStore.addFavorites(this.list, itemsSelected).then(response => {
					this.$router.go({ name: 'items', params: {list: this.list} })
				}, response => {

				});
			},

			removeSelected: function() {

				var itemsSelected = this.selected;

				if ( ! itemsSelected.length) {
					return;
				}

				ItemStore.deleteFavorites(this.list, itemsSelected).then(response => {
					this.mode = 'add';
				}, response => {

				});
			}

		}
	};

</script>

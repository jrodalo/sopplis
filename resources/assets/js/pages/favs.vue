<template>

	<section id="fav-items" class="page">
		<header class="header">
			<div class="header__content">
				<a v-link="{ name: 'items' }" class="header__button">«</a>
				<h1 class="header__title">Productos frequentes</h1>
			</div>
		</header>

		<div class="content content--withfooter">
			<div v-show="state.favorites.length">
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
			<a href="#fav-items" :class="{footer__link: true}" v-show="selected.length" v-on:click.prevent="addSelected">
				Añadir {{ selected.length}} {{ selected.length | pluralize 'producto' }}
			</a>
			<a class="footer__link" v-else>Selecciona los que quieras añadir</a>
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
				list: this.$route.params.list
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
			select: function(item) {
				item.selected = ! item.selected;
			},

			addSelected: function() {
				ItemStore.addItems(this.list, this.selected)
				.then(response => {
					this.$router.go({ name: 'items', params: {list: this.list} })
				}, response => {

				});
			}

		}
	};

</script>

<template>

	<section id="fav-items" class="page">
		<header class="header">
			<div class="header__content">
				<router-link :to="{ name: 'items' }" class="header__button" aria-label="Volver">«</router-link>
				<h1 class="header__title">Productos frecuentes</h1>
				<a v-show="allFavorites.length" v-on:click="changeMode" :class="{'header__button': true, 'header__button--pressed': mode == 'remove'}">✎</a>
			</div>
		</header>

		<div class="content content--withfooter">
			<div v-show="allFavorites.length" :class="{remove: mode == 'remove', add: mode == 'add'}">
				<ul class="list list--flex">
					<li
						v-for="item in allFavorites"
						v-bind:key="item.id"
						v-on:click="select(item)"
						:class="{'item': true, 'item--fav': true, 'item--selected': item.selected}"
						><span>{{ item.name }}</span></li>
				</ul>
			</div>
			<div class="content--centered message message--empty" v-show="!allFavorites.length && !loading">
				<h1 class="message__title">No hay productos frecuentes</h1>
				<p>Los productos frecuentes son aquellos que has comprado varias veces. Sigue utilizando Sopplis para que aparezcan aquí los productos que más compras.</p>
			</div>
		</div>

		<footer class="footer" v-show="allFavorites.length">
			<div v-show="mode=='add'">
				<a href="#fav-items" class="footer__link" v-on:click.prevent="addSelected">
					<span v-show="selectedFavorites.length">Añadir {{ selectedFavorites.length}} productos</span>
					<span v-show="!selectedFavorites.length">Selecciona los que quieras añadir</span>
				</a>
			</div>
			<div v-show="mode!='add'">
				<a href="#fav-items" class="footer__link footer__link--red" v-on:click.prevent="removeSelected">
					<span v-show="selectedFavorites.length">Eliminar {{ selectedFavorites.length}} productos</span>
					<span v-show="!selectedFavorites.length">Selecciona los que quieras eliminar</span>
				</a>
			</div>
		</footer>

	</section>

</template>

<script>

	export default {

		props: {
			list: { required: true }
		},

		created () {
			this.fetchData();
		},

		data () {
			return {
				loading: false,
				mode: 'add'
			}
		},

		computed: {

			allFavorites () {
                return this.$store.getters.allFavorites;
            },

			selectedFavorites () {
				return this.$store.getters.selectedFavorites;
			}
		},

		methods: {

			fetchData () {
                this.loading = true;
                this.$store.dispatch('fetchFavorites', this.list)
                    .then(() => this.loading = false)
                    .catch(() => this.$router.push({ name: '404' }));
            },

			changeMode () {
				this.mode = (this.mode == 'add') ? 'remove' : 'add';
			},

			select (item) {
				item.selected = ! item.selected;
			},

			addSelected () {

				if ( ! this.selectedFavorites.length) {
					return;
				}

				this.$store.dispatch('insertSelected', this.list).then(response => {
					this.$router.push({ name: 'items', params: {list: this.list} });
				});
			},

			removeSelected () {

				if ( ! this.selectedFavorites.length) {
					return;
				}

				this.$store.dispatch('removeSelected', this.list).then(response => {
					this.mode = 'add';
				});
			},
		},
	};

</script>

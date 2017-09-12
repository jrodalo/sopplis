<template>

	<transition-group class="list" name="list-transition" tag="ul">
		<li :class="{'item': true, 'item--done': item.done}" v-for="item in orderedItems" v-bind:key="item.id">
			<span class="item__name">{{item.name}}</span>
			<checkbox :item="item" v-on:changed="update"></checkbox>
		</li>
	</transition-group>

</template>

<script>

	import orderBy from 'lodash.orderby';

	export default {

		components: {
			checkbox: require('../components/checkbox.vue')
		},

		props: {
			list: { required: true }
		},

		computed: {

			orderedItems () {
				return orderBy(this.$store.state.items.all, ['done', item => item.name.toLowerCase()], ['asc', 'asc']);
			}
		},

		methods: {

			update (item) {
				this.$store.dispatch('updateItem', {list: this.list, item});
			}
		},
	};

</script>

<style>

	.list-transition-enter-active {
		transition: all 0.5s;
	}

	.list-transition-move {
		transition: all 0.2s;
	}

	.list-transition-enter {
		opacity: 0;
		background-color: yellow;
	}

</style>

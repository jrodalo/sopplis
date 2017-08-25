<template>
	<ul class="list">
		<li :class="{'item': true, 'item--done': item.done}" v-for="item in orderedItems" v-bind:key="item.id">
			<span class="item__name">{{item.name}}</span>
			<checkbox :item="item" v-on:changed="update"></checkbox>
		</li>
	</ul>
</template>

<script>

	import Item from '../models/Item';
	import orderBy from 'lodash.orderby';

	export default {

		components: {
			checkbox: require('../components/checkbox.vue')
		},

		props: {
			list: { required: true }
		},

		data () {
			return {
				state: Item.state
			}
		},

		computed: {

			orderedItems () {
				return orderBy(this.state.items, ['done', item => item.name.toLowerCase()], ['asc', 'asc']);
			}
		},

		methods: {

			update (item) {
				Item.updateItem(this.list, item);
			}
		}

	};

</script>

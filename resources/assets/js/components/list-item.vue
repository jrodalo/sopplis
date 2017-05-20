<template>
	<ul class="list">
		<li :class="{'item': true, 'item--done': item.done}" v-for="item in orderedItems" v-bind:key="item.id">
			<span class="item__name">{{item.name}}</span>
			<checkbox :list="list" :item="item"></checkbox>
		</li>
	</ul>
</template>

<script>

	import Item from '../models/Item';

	export default {

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
				return _.orderBy(this.state.items, ['done', item => item.name.toLowerCase()], ['asc', 'asc']);
			}
		},

		components: {
			checkbox: require('../components/checkbox.vue')
		}

	};

</script>

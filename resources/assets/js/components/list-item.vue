<template>
	<ul class="list">
		<li :class="{'item': true, 'item--done': item.done}" v-for="item in state.items | orderBy 'done' 'name'" track-by="id">
			<span class="item__name">{{item.name}}</span>
			<checkbox :list="list" :item="item"></checkbox>
		</li>
	</ul>
</template>

<script>

	import ItemStore from '../itemstore';

	export default {

		props: {
			list: { required: true }
		},

		data: function() {
			return {
				state: ItemStore.state
			}
		},

		components: {
			checkbox: require('../components/checkbox.vue')
		}

	};

</script>

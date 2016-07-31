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

<style lang="sass">

	@import "resources/assets/sass/_variables";

	.list {list-style: none; margin: 10px 0;}
	.item {display: flex; align-items: center; background: #FFF; margin: 5px; border-radius: 5px; user-select: none;}
	.item--done {background: darken($back-color, 7);}
	.item__name {flex: 1; padding: 10px;}

</style>

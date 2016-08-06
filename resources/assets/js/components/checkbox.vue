<template>
	<label class="checkbox" v-show="online">
		<input class="checkbox__input" type="checkbox" v-model="item.done">
		<div class="checkbox__tick"></div>
	</label>
</template>

<script>

	import ItemStore from '../itemstore';

	export default {

		props: {
			list: { required: true },
			item: { required: true }
		},

		data: function() {
			return {
				online: true
			}
		},

		watch: {
			'item.done': function() {
				ItemStore.updateItem(this.list, this.item);
			}
		},

		events: {
			online: function(online) {
				this.online = online;
			}
		}
	};

</script>

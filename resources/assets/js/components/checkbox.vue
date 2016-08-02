<template>
	<label class="item__toggle" v-show="online">
		<input class="item__checkbox" type="checkbox" v-model="item.done">
		<div class="item__indicator"></div>
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

<style lang="sass">

	@import "resources/assets/sass/_variables";

	.item__toggle {display: block; position: relative; padding-left: 15px; cursor: pointer;	font-size: 18px;}
	.item__checkbox {opacity: 0;}
	.item__indicator {position: absolute; top: 2px; left: 0; height: 20px; width: 20px; background: #FFF; border: 1px solid darken($back-color, 7); border-radius: 3px;}
	.item__checkbox:checked ~ .item__indicator {background: $dark-color; border: 0;}
	.item__checkbox:active ~ .item__indicator {background: $dark-color;}
	.item__checkbox:focus ~ .item__indicator {border-color: $dark-color;}
	.item__checkbox:checked ~ .item__indicator::after {display: block;}
	.item__indicator::after {
		content: '';
		position: absolute;
		display: none;
		left: 7px;
		top: 4px;
		width: 5px;
		height: 10px;
		border: solid #FFF;
		border-width: 0 2px 2px 0;
		transform: rotate(45deg);
	}

</style>

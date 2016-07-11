<template>
	<ul class="list">
		<li :class="{'item': true, 'item--completed': item.completed}" v-for="item in items | orderBy 'completed' 'name'" track-by="id">
			<span class="item__name">{{item.name}}</span>
			<label class="item__toggle" v-show="onLine">
				<input class="item__checkbox" type="checkbox" v-model="item.completed">
				<div class="item__indicator"></div>
			</label>
		</li>
	</ul>
</template>

<script>

	export default {

		props: {
			items: { required: true, type: Array },
			onLine: { required: true, type: Boolean }
		},

		ready: function () {
			this.loadItems();
		},

		methods: {

			loadItems: function() {

				if (this.onLine) {

					var resource = this.$resource('api/v1/lists{/list}/items{/id}');

					resource.get({'list': '123'}).then((response) => {
						this.$set('items', response.json())
						localStorage.setItem('SOPPLIS_ITEMS', JSON.stringify(this.items));
					});

				} else {
					this.$set('items',  JSON.parse(localStorage.getItem('SOPPLIS_ITEMS')));
				}
			}
		}
	};

</script>

<style lang="sass">

	@import "resources/assets/sass/_variables";

	.list {list-style: none;}
	.item {display: flex; align-items: center; background: #FFF; margin: 5px; border-radius: 5px; user-select: none;}
	.item--completed {background: darken($back-color, 7);}
	.item__name {flex: 1; padding: 10px;}
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

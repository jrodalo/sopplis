<template>
	<label class="checkbox">
		<input class="checkbox__input" type="checkbox" v-model="item.done">
		<div class="checkbox__tick"></div>
	</label>
</template>

<script>

	export default {

		props: {
			item: { required: true }
		},

		watch: {
			'item.done': function() {
				this.$emit('changed', this.item);
			}
		}

	};

</script>

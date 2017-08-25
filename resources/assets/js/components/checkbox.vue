<template>
	<label class="checkbox">
		<input class="checkbox__input" type="checkbox" v-model="item.done" v-on:click="update">
		<div class="checkbox__tick"></div>
	</label>
</template>

<script>

	export default {

		props: {
			item: { required: true }
		},

		methods: {

			update () {
				this.$emit('changed', this.item);
			}
		}

	};

</script>

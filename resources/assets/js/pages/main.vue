<template>

	<div id="app" class="layout" v-cloak>
		<router-view></router-view>
	</div>

</template>

<script>

	export default {

		ready: function() {

			var self = this;

			document.body.addEventListener("offline", function () {
				self.$broadcast('online', false);
			}, false);

		    document.body.addEventListener("online", function () {
				self.$broadcast('online', true);
			}, false);
		}

	};

</script>

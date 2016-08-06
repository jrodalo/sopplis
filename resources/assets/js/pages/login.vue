<template>

	<section id="login" class="page">

		<div class="content loading--centered">
			<loading></loading>
		</div>

	</section>

</template>

<script>

	import User from '../user';

	export default {

		route: {

			activate: function(transition) {

				if (User.isAuthenticated()) {
					transition.redirect({ name: 'lists', query: {} });
				}

				transition.next();
			},

			data: function (transition) {

				return this.$http.get('users?token=' + this.$route.query.token).then((response) => {

					if (response.ok) {
						User.login(response.json().token);
						transition.redirect({ name: 'lists', query: {} });
					}

				}, (response) => {

					transition.redirect({ name: 'home', query: {} });

				});
			}
		},

		components: {
			loading: require('../components/loading.vue')
		}
	};

</script>

<style lang="sass">

	@import "resources/assets/sass/_variables";

	.loading--centered {margin: 100px auto;}
	.loading {color: $dark-color;}

</style>

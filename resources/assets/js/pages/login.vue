<template>

	<section id="login" class="page">

		<div class="content content--centered">
			<div class="la-ball-fall loading">
				<div></div>
				<div></div>
				<div></div>
			</div>
		</div>

	</section>

</template>

<script>

	import User from '../user';

	export default {

		route: {

			activate: function(transition) {

				if (User.isAuthenticated()) {
					transition.redirect('lists');
				}

				transition.next();
			},

			data: function (transition) {

				return this.$http.get('users?token=' + this.$route.query.token).then((response) => {

					if (response.ok) {
						User.login(response.json().token);
						transition.redirect('lists');
					}

				}, (response) => {

					transition.redirect('/');

				});
			}
		}

	};

</script>

<style lang="sass">

	@import "resources/assets/sass/_variables";

	.content--centered {margin: 100px auto;}
	.loading {color: $dark-color;}

</style>

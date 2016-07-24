<template>

	<section id="login" class="page">

		<div class="content">

			<h1 class="title">Sopplis</h1>

			<form class="form" v-on:submit.prevent="sendData" v-show=" ! submited">
				<p><input type="text" id="email" name="email" placeholder="Email" v-model="email" class="" required autofocus></p>
				<p><input type="submit" value="Entrar" ></p>
			</form>

			<p v-show="submited">Se ha enviado un correo con la llave para entrar <a href="{{ mailServer }}">ir</a></p>
		</div>

	</section>

</template>

<script>

	import User from '../user';

	export default {

		route: {

			activate: function (transition) {

				if (User.isAuthenticated()) {
					transition.redirect('lists');
				}

				transition.next();
			}
		},

		data: function() {
			return {
				email: '',
				submited: false
			}
		},

		computed: {
			mailServer: function() {
				return 'http://' + this.email.split('@')[1];
			}
		},

		methods: {

			sendData: function() {

				var email = this.email && this.email.trim();

				if ( ! email) {	return;	}

				this.$http.post('users', {email: email}).then((response) => {

					if (response.ok) {
						this.submited = true;
					}

				}, (response) => {

					console.log(response);

				});
			}
		}

	};

</script>

<style lang="sass">

.title {margin: 30px 0;}
.form {}

</style>

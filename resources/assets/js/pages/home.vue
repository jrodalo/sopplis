<template>

	<section id="login" class="page">

		<div class="content content--centered">

			<div class="login__form" v-show=" ! submited">
				<h1 class="title">Sopplis</h1>
				<form v-on:submit.prevent="sendData">
					<p><input type="email" class="login__input" id="email" name="email" placeholder="¿Cuál es tu email?" v-model="email" required autofocus></p>
					<p><input type="submit" class="login__button" value="Entrar"></p>
				</form>
			</div>

			<div class="login__form" v-show="submited">
				<h2>¡Gracias!</h2>
				<p>Te he enviado <a href="{{ mailServer }}">un correo</a> con la llave que necesitas para entrar en Sopplis.</p>
			</div>

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

				});
			}
		}

	};

</script>

<style lang="sass">

.title {margin: 30px 0;}
.login__form {margin: 20px;}
.login__input {padding: 5px 10px; width: 100%; max-width: 400px; border: 1px solid #CCC; border-radius: 3px;}
.login__input:focus {outline: 0;}
.login__input::placeholder {text-align: center;}
.login__button {margin-top: 20px; background: rgb(66, 184, 221); border-radius: 3px; border: 1px solid transparent; padding: .5em 1em; color: #FFF; text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);}
.login__button:active {box-shadow: 0 0 0 1px rgba(0,0,0,.15) inset,0 0 6px rgba(0,0,0,.2) inset; outline: 0;}
.login__button:focus {outline: 0; border: 1px solid #2c7e98;}

</style>

<template>

	<section id="login" class="page">

		<div class="content content--centered">

			<div class="form" v-show=" ! submited">
				<h1 class="title">Sopplis</h1>
				<form v-on:submit.prevent="sendData">
					<p><input
							type="email"
							id="email"
							name="email"
							autocomplete="email"
							class="form__input"
							placeholder="¿Cuál es tu email?"
							v-model="email"
							required
							autofocus></p>
					<p><input type="submit" class="form__button" value="Entrar"></p>
				</form>
			</div>

			<div class="form" v-show="submited">
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

<template>

	<section id="login" class="page">

		<div class="content content--centered">

			<div class="form" v-show=" ! submited">

				<img src="/favicon-touch.png" alt="Logo" class="logo" width="80" height="80">
				<h1 class="title">Sopplis</h1>
				<h2 class="subtitle">Hacer listas de la compra en papel es cosa del pasado</h2>

				<form v-on:submit.prevent="sendData">
					<p><input
							type="email"
							id="email"
							name="email"
							autocomplete="email"
							class="form__input"
							placeholder="Email"
							v-model="email"
							required></p>
					<p><input type="submit" class="form__button" value="Entrar en Sopplis"></p>
				</form>
			</div>

			<div class="form" v-show="submited">
				<h1>¡Perfecto!</h1>
				<p>Te he enviado <a href="{{ mailServer }}">un correo</a> con la llave que necesitas para entrar en Sopplis.</p>
				<p class="form__note">Revisa la carpeta de Spam si el correo no te llega en los próximos minutos.</p>
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

					if (response.ok && response.json().success === true) {
						this.submited = true;
					}

				}, (response) => {

				});
			}
		}

	};

</script>

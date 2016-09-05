<template>

	<section id="login" class="page">

		<div class="content content--centered">

			<div class="form">

				<img src="/favicon-touch.png" alt="Logo" class="logo" width="80" height="80">
				<h1 class="title">Sopplis</h1>
				<h2 class="subtitle">Próximamente</h2>

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
				password: ''
			}
		},

		methods: {

			login: function() {

				var email = this.email && this.email.trim();
				var password = this.password;

				if ( ! email || ! password) { return; }

				User.login({email: email, password: password}).then((response) => {

					if (response.ok && response.json().success === true) {
						this.$router.go({ path: '/lists' });
					}

				}).catch((response) => {
					sweetAlert({
						title: 'Usuario no válido',
						text: 'Parece que el usuario o contraseña que has puesto no son válidos.',
						type: 'error',
						confirmButtonText: 'Vale'
					});
				});
			}
		}

	};

</script>

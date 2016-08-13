<template>

	<section id="config" class="page">

		<header class="header">
			<div class="header__content">
				<a v-link="{ name: 'home', params: {} }" class="header__button">«</a>
				<h1 class="header__title">Configuración</h1>
			</div>
		</header>

		<div class="content">
			<form class="form" v-on:submit.prevent="save">
				<p>
					<label for="name">¿Cómo te llamas?</label>
					<input
							type="text"
							id="name"
							name="name"
							v-model="name"
							class="form__input"
							autocomplete="off"
							autocapitalize="words"
							maxlength="100"
							required>
					<p class="form__note">Cuando compartas una lista, la otra persona verá este nombre en lugar de tu dirección de correo.</p>
				</p>
				<p><input type="submit" value="Guardar" class="form__button"></p>
			</form>
		</div>

		<footer class="footer">
			<a href="#" v-on:click="salir" class="footer__link footer__link--red">Salir de Sopplis</a>
		</footer>
	</section>

</template>

<script>

	import User from '../user';

	export default {

		data: function() {
			return {
				name: User.name()
			}
		},

		methods: {

			save: function() {
				if (this.name.trim()) {
					User.updateUser(this.name).then(() => {
						sweetAlert({
								title: '¡Bonito nombre!',
								timer: 2000,
								type: 'success',
								showConfirmButton: false});
					}).catch(() => {
						sweetAlert({
								title: 'Upss!',
								text: 'No he podido cambiar tu nombre... vuelve a intentarlo en otro momento',
								confirmButtonText: 'Ok',
								type: 'error'});
					});
				}
			},

			salir: function() {

				var self = this;

				sweetAlert({
					  title: '¿Quieres salir de Sopplis?',
					  type: 'info',
					  showCancelButton: true,
					  closeOnConfirm: true
					},
					function() {
						User.logout();
						self.$router.go({ name: 'home' });
					}
				);
			}
		}
	};

</script>

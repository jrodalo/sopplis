<template>

	<section id="config" class="page">

		<header class="header">
			<div class="header__content">
				<router-link :to="{ name: 'lists', params: {} }" class="header__button">«</router-link>
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
							maxlength="100"
							required>
					<p class="form__note">Cuando compartas una lista, la otra persona verá este nombre en lugar de tu dirección de correo.</p>
				</p>
				<p><input type="submit" value="Guardar" class="form__button"></p>
			</form>
		</div>

		<footer class="footer">
			<a href="#config" v-on:click.prevent="logout" class="footer__link footer__link--red">Salir de Sopplis</a>
		</footer>
	</section>

</template>

<script>

	import User from '../models/User';

	export default {

		data () {
			return {
				name: User.data().name || ''
			}
		},

		methods: {

			save () {
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

			logout () {

				sweetAlert({
					  title: '¿Quieres salir de Sopplis?',
					  type: 'info',
					  showCancelButton: true,
					  closeOnConfirm: true
					},
					() => {
						User.logout();
						this.$router.push({ name: 'login' });
					}
				);
			}
		}
	};

</script>

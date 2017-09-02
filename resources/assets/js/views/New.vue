<template>

	<section id="lists" class="page">
		<header class="header">
			<div class="header__content">
				<router-link :to="{ name: 'lists' }" class="header__button">«</router-link>
				<h1 class="header__title">Nueva lista</h1>
			</div>
		</header>

		<div class="content">
			<form class="form" v-on:submit.prevent="addList">
				<p>
					<label for="name">Nombre de la lista:</label>
					<input
							:placeholder="placeholder"
							v-model="name"
							type="text"
							id="name"
							name="name"
							class="form__input"
							autocomplete="off"
							maxlenght="100"
							required>
				</p>
				<p><input type="submit" value="Guardar" class="form__button"></p>
			</form>
		</div>
	</section>

</template>

<script>

	export default {

		mounted () {
			this.changePlaceholder() & setInterval(this.changePlaceholder, 3000);
		},

		data () {
			return {
				placeholders: ['Lista de la compra', 'Cumpleaños de Elisa', 'Comida para fin de año', 'Fiesta de despedida'],
				placeholder: '',
				name: '',
			}
		},

		methods: {

			changePlaceholder () {
				var position = this.placeholders.indexOf(this.placeholder) + 1;
				position = position >= this.placeholders.length ? 0 : position;
				this.placeholder = this.placeholders[position];
			},

			addList () {

				if (! this.name) {
					return;
				}

				this.$store.dispatch('createList', {name: this.name}).then(() => {
					this.$router.push({ name: 'lists' });
				}, () => {
					sweetAlert('Oops...', 'No he podido crear tu lista... vuelve a intentarlo :(', 'error');
				});
			}
		}
	};

</script>

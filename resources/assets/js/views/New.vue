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
							type="text"
							id="name"
							name="name"
							v-model="name"
							class="form__input"
							:placeholder="placeholder"
							autocomplete="off"
							maxlenght="100"
							required>
				</p>
				<p>
					<label for="emails">
						Compartir con<span v-show="mailCount > 0"> <b>{{ mailCount }}</b> personas</span>:
					</label>
					<textarea
							id="emails"
							name="emails"
							v-model="emails"
							class="form__input"
							rows="4"
							autocapitalize="none"
							placeholder="Escribe un email por línea (máximo 5)"></textarea>
				</p>
				<p><input type="submit" value="Guardar" class="form__button"></p>
			</form>
		</div>
	</section>

</template>

<script>

	import List from '../models/List'

	export default {

		mounted () {
			this.changePlaceholder() & setInterval(this.changePlaceholder, 3000);
		},

		data () {
			return {
				placeholders: ['Lista de la compra', 'Cumpleaños de Elisa', 'Comida para fin de año', 'Fiesta de despedida'],
				placeholder: '',
				name: '',
				emails: ''
			}
		},

		computed: {

			mailCount () {
				return List.splitEmails(this.emails).length;
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

				List.addList({name: this.name, emails: List.splitEmails(this.emails)}).then((response) => {
					this.$router.push({ name: 'lists' });
				}, (response) => {
					sweetAlert('Oops...', 'No he podido crear tu lista... vuelve a intentarlo :(', 'error');
				});
			}
		}
	};

</script>

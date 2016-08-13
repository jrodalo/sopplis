<template>

	<section id="lists" class="page">
		<header class="header">
			<div class="header__content">
				<a v-link="{name: 'config' }" class="header__button">☰</a>
				<h1 class="header__title">Mis listas</h1>
				<a v-link="{ name: 'new' }" class="header__button">+</a>
			</div>
		</header>

		<div class="content">
			<div v-show="state.lists.length">
				<ul class="list list--flex">
					<li v-for="list in state.lists" class="item item--taller">
						<a class="item__name--flex" v-link="{ name: 'items', params: { list: list.slug }}">
							<span>{{ list.name }}</span>
							<i v-show="list.shared" title="Lista compartida">⚭</i>
						</a>
					</li>
				</ul>
			</div>
			<div class="content--centered message message--empty" v-show="!state.lists.length && !$loadingRouteData">
				<h1 class="message__title">Esto está vacío :(</h1>
				<p>Es hora de crear tu primera lista pulsando el botón <b>+</b></p>
			</div>
		</div>
	</section>

</template>

<script>

	import SweetAlert from 'sweetalert';
	import User from '../user';
	import ListStore from '../liststore';

	export default {

		route: {

			data: function (transition) {
				return ListStore.readLists().catch(() => {
					this.$router.go({ path: '/404' });
				});
			}
		},

		data: function() {
			return {
				state: ListStore.state
			}
		}

	};

</script>

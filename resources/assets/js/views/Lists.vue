<template>

	<section id="lists" class="page">
		<header class="header">
			<div class="header__content">
				<router-link :to="{name: 'config'}" class="header__button">☰</router-link>
				<h1 class="header__title">Mis listas</h1>
				<router-link :to="{name: 'new'}" class="header__button">+</router-link>
			</div>
		</header>

		<div class="content">
			<div v-show="state.lists.length">
				<ul class="list list--flex">
					<li v-for="list in state.lists" class="item item--taller">
						<router-link :to="{name: 'items', params: { list: list.slug }}" class="item__name--flex">
							<span>{{ list.name }}</span>
							<i v-show="list.shared" title="Lista compartida">⚭</i>
						</router-link>
					</li>
				</ul>
			</div>
			<div class="content--centered message message--empty" v-show="!state.lists.length">
				<h1 class="message__title">No tienes ninguna lista :(</h1>
				<p>Es hora de crear tu primera lista pulsando el botón <b>+</b></p>
			</div>
		</div>
	</section>

</template>

<script>

	import User from '../models/User';
	import List from '../models/List';

	export default {

		created () {
			this.fetchData()
		},

		data () {
			return {
				state: List.state
			}
		},

		methods: {

			fetchData () {
				return List.readLists();
			}
		}

	};

</script>

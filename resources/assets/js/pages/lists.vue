<template>

	<section id="lists" class="page">
		<header class="header">
			listas
		</header>

		<div class="content">
			<ul class="list">
				<li v-for="list in lists" class="item">
					<a v-link="{ name: 'items', params: { slug: list.slug }}">{{ list.name }}</a>
				</li>
			</ul>
		</div>

		<footer class="footer">

		</footer>
	</section>

</template>

<script>

	import SweetAlert from 'sweetalert';

	export default {

		data: function() {
			return {
				lists: [],
				onLine: navigator.onLine
			}
		},

		components: {

		},

		computed: {

		},

		methods: {

		},

		created: function() {
			window.addEventListener('online',  function(){
			    vm.onLine = true;
			});

			window.addEventListener('offline',  function(){
			    vm.onLine = false;
			});


			var resource = this.$resource('/api/v1/lists/');

			resource.get().then((response) => {

				if (response.ok) {
					this.$set('lists', response.json().lists);
					localStorage.setItem('SOPPLIS_LISTS', JSON.stringify(this.lists));
				}

			}, (response) => {
  				sweetAlert('Oops...', 'No he podido leer la lista... vuelve a intentarlo :(', 'error');
  				this.$set('lists',  JSON.parse(localStorage.getItem('SOPPLIS_LISTS')));
				});
		}
	};

</script>

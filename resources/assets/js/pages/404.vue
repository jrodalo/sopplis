<template>

	<section id="notfound" class="page">

		<header class="header">
			<div class="header__content">
				<a v-link="{ name: 'home', params: {} }" class="header__button">«</a>
				<h1 class="header__title">Página no encontrada</h1>
			</div>
		</header>

		<div class="content content--centered message message--empty">
			<h1 class="message__title">Upss!</h1>
			<p>Parece que la página que buscas no existe o ha cambiado de sitio...</p>
		</div>

	</section>

</template>

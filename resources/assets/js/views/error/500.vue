<template>

	<section id="notfound" class="page">

		<header class="header">
			<div class="header__content">
				<router-link :to="{ name: 'login', params: {} }" class="header__button" aria-label="Volver">«</router-link>
				<h1 class="header__title">Algo ha fallado :(</h1>
			</div>
		</header>

		<div class="content content--centered message message--empty">
			<h1 class="message__title">¡Vaya!</h1>
			<p>Parece que ha fallado algo que no debía fallar...</p>
			<p>Por favor, vuelve a intentarlo en otro momento.</p>
		</div>

	</section>

</template>

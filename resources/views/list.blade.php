@extends('layouts.app')

@section('scripts')
	<script src="/js/app.js"></script>
@endsection

@section('content')

	<div id="app" class="layout" v-cloak>

		<section id="active-items" class="page">
			<header class="header">
				<create-item :items.sync="items" :on-line="onLine"></create-item>
			</header>

			<div class="content">
				<list-item :items.sync="items" :on-line="onLine"></list-item>
			</div>

			<footer class="footer">
				<a href="#completed-items" class="footer__link" v-on:click.prevent="finalize">@{{ completedItems.length }}</a>
			</footer>
		</section>

	</div>

@endsection

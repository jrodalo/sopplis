@extends('layouts.main')

@section('scripts')
	<script src="/js/app.js"></script>
@endsection

@section('content')

	<div id="app" class="layout" v-cloak>

		<router-view></router-view>

	</div>

@endsection

@extends('layouts.main')

@section('scripts')
    <script src="{{ mix('js/manifest.js') }}"></script>
	<script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
@endsection

@section('content')
	<div id="app"><router-view></router-view></div>
@endsection
